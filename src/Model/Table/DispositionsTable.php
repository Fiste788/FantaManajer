<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Dispositions Model
 *
 * @property \App\Model\Table\LineupsTable&\Cake\ORM\Association\BelongsTo $Lineups
 * @property \App\Model\Table\MembersTable&\Cake\ORM\Association\BelongsTo $Members
 * @property \Cake\ORM\Table&\Cake\ORM\Association\HasOne $Rating
 * @method \App\Model\Entity\Disposition get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Disposition newEntity(array<mixed> $data, array<string, mixed> $options = [])
 * @method \App\Model\Entity\Disposition[] newEntities(array<mixed> $data, array<string, mixed> $options = [])
 * @method \App\Model\Entity\Disposition|false save(\Cake\Datasource\EntityInterface $entity, array<string, mixed> $options = [])
 * @method \App\Model\Entity\Disposition saveOrFail(\Cake\Datasource\EntityInterface $entity, array<string, mixed> $options = [])
 * @method \App\Model\Entity\Disposition patchEntity(\Cake\Datasource\EntityInterface $entity, array<mixed> $data, array<string, mixed> $options = [])
 * @method \App\Model\Entity\Disposition[] patchEntities(iterable<\Cake\Datasource\EntityInterface> $entities, array<mixed> $data, array<string, mixed> $options = [])
 * @method \App\Model\Entity\Disposition findOrCreate(\Cake\ORM\Query\SelectQuery|callable|array $search, ?callable $callback = null, array<string, mixed> $options = [])
 * @method \App\Model\Entity\Disposition newEmptyEntity()
 * @method \App\Model\Entity\Disposition[]|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Disposition>|false saveMany(iterable<\Cake\Datasource\EntityInterface> $entities, array<string, mixed> $options = [])
 * @method \App\Model\Entity\Disposition[]|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Disposition> saveManyOrFail(iterable<\Cake\Datasource\EntityInterface> $entities, array<string, mixed> $options = [])
 * @method \App\Model\Entity\Disposition[]|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Disposition>|false deleteMany(iterable<\Cake\Datasource\EntityInterface> $entities, array<string, mixed> $options = [])
 * @method \App\Model\Entity\Disposition[]|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Disposition> deleteManyOrFail(iterable<\Cake\Datasource\EntityInterface> $entities, array<string, mixed> $options = [])
 */
class DispositionsTable extends Table
{
    /**
     * Initialize method
     *
     * @param array<string, mixed> $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('dispositions');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Lineups', [
            'foreignKey' => 'lineup_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Members', [
            'foreignKey' => 'member_id',
            'joinType' => 'INNER',
        ]);
        $this->hasOne('Rating', [
            'finder' => 'byMatchdayLineup',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     * @throws \InvalidArgumentException
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->integer('position')
            ->requirePresence('position', 'create')
            ->greaterThan('position', 0);

        $validator
            ->integer('considetarion')
            ->nonNegativeInteger('consideration');

        $validator
            ->numeric('points')
            ->allowEmptyString('points');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     * @throws \Cake\Core\Exception\CakeException If a rule with the same name already exists
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn(['lineup_id'], 'Lineups'));
        $rules->add($rules->existsIn(['member_id'], 'Members'));

        return $rules;
    }

    /**
     * Find by matchday lineup query
     *
     * @param \Cake\ORM\Query\SelectQuery $query Query
     * @param mixed ...$args Options
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function findByMatchdayLineup(SelectQuery $query, mixed ...$args): SelectQuery
    {
        return $query->innerJoinWith('Lineups')
            ->where(['Ratings.matchday_id' => 'Lineups.matchday_id'])
            ->andWhere(['Ratings.member_id' => 'member_id']);
    }
}
