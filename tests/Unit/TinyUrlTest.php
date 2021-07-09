<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\TinyUrl;

class TinyUrlTest extends TestCase
{


    /**
     * @var object
     */
    protected $tinyUrl;

    /**
     * Setup function
     * 
     * @return void
     */

    public function setUp(): void
    {
        parent::setUp();
        $this->tinyUrl = new TinyUrl();
    }

    /**
     * 
     * @return void
     */
    public function testGetTinyUrlFromLongUrlWIthoutExpiryDate(): void
    {
        $response = $this->tinyUrl->getTinyUrlFromLongUrl('google.com');
        $this->assertNotEmpty($response);
        $this->assertIsString($response);
        $this->assertEquals($response, '3');
    }

    /**
     * 
     * @return void
     */
    public function testGetTinyUrlFromLongUrlWIthExpiryDate(): void
    {
        $response = $this->tinyUrl->getTinyUrlFromLongUrl('google.com','2021-07-18');
        $this->assertNotEmpty($response);
        $this->assertIsString($response);
        $this->assertEquals($response, '3');
    }

    /**
     * 
     * @return void
     */
    public function testUrlExistsInDbsWithoutExpiryDate(): void
    {
        $response = $this->tinyUrl->urlExistsInDb('google.com');
        $this->assertNotEmpty($response);
        $this->assertIsString($response);
        $this->assertEquals($response, '3');
    }

    /**
     * 
     * @return void
     */
    public function testUrlExistsInDbWithExpiryDate(): void
    {
        $response = $this->tinyUrl->urlExistsInDb('google.com','2021-09-09');
        $this->assertNotEmpty($response);
        $this->assertIsString($response);
        $this->assertEquals($response, '3');
    }

    /**
     * 
     * @return void
     */
    public function testUrlExistsInDbForUrlNotPresentInDatabase(): void
    {
        $response = $this->tinyUrl->urlExistsInDb('google');
        $this->assertEmpty($response);
    }

    /**
     * 
     * @return void
     */
    public function testInsertShortCodeInDb(): void
    {
        $response = $this->tinyUrl->insertShortCodeInDb('5','5');
        $this->assertNull($response);
    }

    /**
     * 
     * @return void
     */
    public function testGetShortCodeFromId(): void
    {
        $response = $this->tinyUrl->getShortCodeFromId(5);
        $this->assertNotEmpty($response);
        $this->assertEquals($response, '5');
    }

    /**
     * 
     * @return void
     */
    public function testInsertUrlInDbWithoutExpiryDate(): void
    {
        $response = $this->tinyUrl->insertUrlInDb('google.cz');
        $this->assertNotEmpty($response);
    }

    /**
     * 
     * @return void
     */
    public function testInsertUrlInDbWithExpiryDate(): void
    {
        $response = $this->tinyUrl->insertUrlInDb('google.cz','2021-09-19');
        $this->assertNotEmpty($response);
    }

    /**
     * 
     * @return void
     */
    public function testGetLongUrlFromDb(): void
    {
        $this->tinyUrl = new TinyUrl();
        $response = $this->tinyUrl->getLongUrlFromDb(3);
        $this->assertNotEmpty($response);
        $this->assertEquals($response, 'google.com');
    }

    /**
     * Test setRedirectTinyUrlVisitCount
     * @return void
     */
    public function testSetRedirectTinyUrlVisitCount(): void
    {
        $id = 12;
        $response = $this->tinyUrl->setRedirectTinyUrlVisitCount($id, 2);
        $urlVisit = $this->tinyUrl->geturlVisitForATinyUrlFromDatabase($id);
        $this->assertNull($response);
        $this->assertEquals(3,$urlVisit);
    }
}
