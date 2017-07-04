<?php
namespace App\Model\Table;

use App\Model\Entity\Transfert;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Transferts Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Members
 * @property \Cake\ORM\Association\BelongsTo $Members
 * @property \Cake\ORM\Association\BelongsTo $Teams
 * @property \Cake\ORM\Association\BelongsTo $Matchdays
 */
class TransfertsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('transferts');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->belongsTo('Members', [
            'foreignKey' => 'old_member_id'
        ]);
        $this->belongsTo('Members', [
            'foreignKey' => 'new_member_id'
        ]);
        $this->belongsTo('Teams', [
            'foreignKey' => 'team_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Matchdays', [
            'foreignKey' => 'matchday_id',
            'joinType' => 'INNER'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->boolean('constrained')
            ->requirePresence('constrained', 'create')
            ->notEmpty('constrained');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['old_member_id'], 'Members'));
        $rules->add($rules->existsIn(['new_member_id'], 'Members'));
        $rules->add($rules->existsIn(['team_id'], 'Teams'));
        $rules->add($rules->existsIn(['matchday_id'], 'Matchdays'));
        return $rules;
    }
}
