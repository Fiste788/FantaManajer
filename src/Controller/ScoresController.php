<?php
declare(strict_types=1);

namespace App\Controller;

use App\Model\Entity\Lineup;
use Cake\Event\Event;
use Cake\Event\EventInterface;

/**
 *
 * @property \App\Model\Table\ScoresTable $Scores
 * @property \App\Service\LineupService $Lineup
 */
class ScoresController extends AppController
{
    /**
     * @inheritDoc
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->loadService('Lineup');
    }

    /**
     * @inheritDoc
     */
    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);
        $this->Crud->mapAction('edit', 'Crud.Edit');
    }

    /**
     * View
     *
     * @param int $id Id
     * @return \Cake\Http\Response
     */
    public function view($id)
    {
        $members = $this->request->getQuery('members');
        $that = $this;
        $this->Crud->on('afterFind', function (Event $event) use ($members, $that) {
            $result = $this->Scores->loadDetails($event->getSubject()->entity, $members);
            if ($members) {
                if (!$result->lineup) {
                    $result->lineup = $that->Lineup->newLineup($result->team_id, $result->matchday_id);
                }
                $result->lineup->modules = Lineup::$module;
            }

            return $result;
        });

        return $this->Crud->execute();
    }

    /**
     * Edit
     *
     * @return \Cake\Http\Response
     */
    public function edit()
    {
        $this->Crud->action()->saveOptions(['associated' => [
            'Lineups' => [
                'accessibleFields' => ['id' => true],
                'associated' => ['Dispositions'],
            ],
        ]]);

        $this->Crud->on('afterSave', function (\Cake\Event\Event $event) {
            $event->getSubject()->entity->compute();
            $this->Scores->save($event->getSubject()->entity);
        });

        return $this->Crud->execute();
    }
}
