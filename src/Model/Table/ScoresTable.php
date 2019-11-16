<?php
declare(strict_types=1);

namespace App\Model\Table;

use App\Model\Entity\Score;
use App\Model\Entity\Season;
use Cake\Collection\CollectionInterface;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Scores Model
 *
 * @property \App\Model\Table\TeamsTable|\Cake\ORM\Association\BelongsTo $Teams
 * @property \App\Model\Table\MatchdaysTable|\Cake\ORM\Association\BelongsTo $Matchdays
 * @property \Cake\ORM\Association\HasOne $Lineup
 * @property \App\Model\Table\LineupsTable|\Cake\ORM\Association\BelongsTo $Lineups
 * @method \App\Model\Entity\Score get($primaryKey, $options = [])
 * @method \App\Model\Entity\Score newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Score[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Score|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Score patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Score[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Score findOrCreate($search, callable $callback = null, $options = [])
 * @method \App\Model\Entity\Score|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 */
class ScoresTable extends Table
{
    /**
     * @inheritDoc
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('scores');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo(
            'Lineups',
            [
                'foreignKey' => 'lineup_id',
            ]
        );
        $this->belongsTo(
            'Teams',
            [
                'foreignKey' => 'team_id',
                'joinType' => 'RIGHT',
            ]
        );
        $this->belongsTo(
            'Matchdays',
            [
                'foreignKey' => 'matchday_id',
                'joinType' => 'INNER',
            ]
        );
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->numeric('points')
            ->requirePresence('points', 'create')
            ->notEmpty('points');

        $validator
            ->numeric('real_points')
            ->requirePresence('real_points', 'create')
            ->notEmpty('real_points');

        $validator
            ->numeric('penality_points')
            ->allowEmpty('penality_points');

        $validator
            ->allowEmpty('penality');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn(['team_id'], 'Teams'));
        $rules->add($rules->existsIn(['matchday_id'], 'Matchdays'));
        $rules->add($rules->existsIn(['lineup_id'], 'Lineups'));

        return $rules;
    }

    /**
     *
     * @param \App\Model\Entity\Season $season Season
     * @return int
     */
    public function findMaxMatchday(Season $season): int
    {
        $query = $this->find();
        $res = $query->disableHydration()
            ->join(
                [
                    'table' => 'matchdays',
                    'alias' => 'm',
                    'type' => 'LEFT',
                    'conditions' => 'm.id = Scores.matchday_id',
                ]
            )
            ->select(['matchday_id' => $query->func()->max('Scores.matchday_id'),])
            ->where(['m.season_id' => $season->id])
            ->first();

        return $res['matchday_id'];
    }

    /**
     * Find scores query
     *
     * @param \Cake\ORM\Query $q Query
     * @param array $options Options
     * @return \Cake\ORM\Query
     */
    public function findScores(Query $q, array $options): Query
    {
        $championshipId = $options['championship_id'];

        return $q->select(['id', 'points', 'team_id'])
            ->contain([
                'Matchdays' => ['fields' => ['number']],
            ])->innerJoinWith('Teams', function ($q) use ($championshipId) {
                return $q->where(['Teams.championship_id' => $championshipId]);
            })->formatResults(function (CollectionInterface $results) {
                return $results->combine('matchday.number', function ($entity) {
                    unset($entity->matchday);

                    return $entity;
                }, 'team_id');
            }, true);
    }

    /**
     * Find by team query
     *
     * @param \Cake\ORM\Query $q Query
     * @param array $options Options
     * @return \Cake\ORM\Query
     */
    public function findByTeam(Query $q, array $options): Query
    {
        return $q->contain([
            'Teams',
            'Matchdays' => ['fields' => ['number']],
        ])->where([
            'team_id' => $options['team_id'],
        ]);
    }

    /**
     * Find ranking query
     *
     * @param \Cake\ORM\Query $q Query
     * @param array $options Options
     * @return \Cake\ORM\Query
     */
    public function findRanking(Query $q, array $options): Query
    {
        $championshipId = $options['championship_id'];
        $sum = $q->func()->sum('points');
        $coalesce = $q->func()->coalesce([$sum, 0], ['float', 'float']);
        $q->select([
            'Teams.id',
            'sum_points' => $coalesce,
        ])->contain(['Teams' => ['fields' => ['id', 'name', 'championship_id']]])
            ->where(['Teams.championship_id' => $championshipId])
            ->group('Teams.id')
            ->orderDesc($sum);

        if (array_key_exists('scores', $options) && $options['scores']) {
            $q->formatResults(function (CollectionInterface $results) use ($championshipId) {
                $scores = $this->find('scores', [
                    'championship_id' => $championshipId,
                ])->all()->toArray();

                return $results->map(function (Score $entity) use ($scores) {
                    $entity['scores'] = $scores[$entity->team->id];

                    return $entity;
                });
            }, true);
        }

        return $q;
    }

    /**
     * Load score details
     *
     * @param \App\Model\Entity\Score $score Score
     * @param bool $members Members
     * @return \App\Model\Entity\Score
     */
    public function loadDetails(Score $score, $members = false): Score
    {
        if ($members) {
            $contain = [
                'Lineups' => [
                    'Dispositions', 'Teams.Members' => [
                        'Roles', 'Players',
                    ],
                ],
            ];
        } else {
            $contain = [
                'Lineups.Dispositions.Members' => [
                    'Roles', 'Players', 'Clubs', 'Ratings' => function (Query $q) use ($score) {
                        return $q->where(['Ratings.matchday_id' => $score->matchday_id]);
                    },
                ],
            ];
        }

        return $this->loadInto($score, $contain);
    }
}
