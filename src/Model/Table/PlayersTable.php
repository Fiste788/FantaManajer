<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Players Model
 *
 * @property \App\Model\Table\MembersTable&\Cake\ORM\Association\HasMany $Members
 * @method \App\Model\Entity\Player get($primaryKey, $options = [])
 * @method \App\Model\Entity\Player newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Player[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Player|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Player saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Player patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Player[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Player findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Player newEmptyEntity()
 * @method \App\Model\Entity\Player[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Player[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Player[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Player[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class PlayersTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('players');
        $this->setDisplayField('surname');
        $this->setPrimaryKey('id');

        $this->hasMany('Members', [
            'foreignKey' => 'player_id',
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
            ->scalar('name')
            ->maxLength('name', 30)
            ->allowEmptyString('name');

        $validator
            ->scalar('surname')
            ->maxLength('surname', 30)
            ->requirePresence('surname', 'create')
            ->notEmptyString('surname');

        return $validator;
    }

    /**
     * Find with details query
     *
     * @param \Cake\ORM\Query $q Query
     * @param array $options Options
     * @return \Cake\ORM\Query
     */
    public function findWithDetails(Query $q, array $options): Query
    {
        return $q->contain(['Members' => function (Query $q): Query {
            return $q->contain(['Seasons', 'Roles', 'Clubs'])->orderDesc('Seasons.year');
        }]);
    }
}
