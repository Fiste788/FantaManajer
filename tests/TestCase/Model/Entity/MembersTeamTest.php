<?php
namespace App\Test\TestCase\Model\Entity;

use App\Model\Entity\MembersTeam;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Entity\MembersTeam Test Case
 */
class MembersTeamTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Entity\MembersTeam
     */
    public $MembersTeam;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->MembersTeam = new MembersTeam();
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->MembersTeam);

        parent::tearDown();
    }

    /**
     * Test initial setup
     *
     * @return void
     */
    public function testInitialization()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
