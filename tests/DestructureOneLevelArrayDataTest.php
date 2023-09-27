<?php

declare(strict_types=1);

require_once('src/DestructureApiData/DestructureApiData.php');

use PHPUnit\Framework\TestCase;

use Tommydo\DestructureApiData\DestructureApiData;

final class DestructureOneLevelArrayDataTest extends TestCase
{
    private $mock_1_level_json_data = '{
        "films"    : "https://swapi.dev/api/films/",
        "people"   : "https://swapi.dev/api/people/",
        "planets"  : "https://swapi.dev/api/planets/",
        "species"  : "https://swapi.dev/api/species/",
        "starships": "https://swapi.dev/api/starships/",
        "vehicles" : "https://swapi.dev/api/vehicles/"
    }';

    private $one_level_array_w_2_item_schema = [
        'films'  => 'my_films',
        'people' => 'my_people'
    ];

    private $one_level_array_full_schema = [
        'films'     => 'my_films',
        'people'    => 'my_people',
        'planets'   => 'my_planet',
        'species'   => 'my_specie',
        'starships' => 'my_starship',
        'vehicles'  => 'my_vehicle',
    ];

    /**
     * ***********************************
     * ********** FRIENDLY TEST **********
     * ***********************************
     */

    /**
     * Test 1-level array data
     * Return an array
     */
    public function testServiceReturnAnArray(): void
    {
        $expecteds_array_schema = $this->one_level_array_w_2_item_schema;
        $mock_json_data = json_decode($this->mock_1_level_json_data, true);

        $Destructor = new DestructureApiData();
        $Destructor->setOption('keep_origin', 0);

        // Set expected return schema;
        $Destructor->set('schema', $expecteds_array_schema);
        // Set API responsed data
        $Destructor->set('original_data', $mock_json_data);

        $my_api_data = $Destructor->destruct();

        $this->assertIsArray($my_api_data, 'Test does not receicve an array');
    }

    /**
     * Test 1-level array data
     * 2/6 keys defined for schema
     * No keep original data
     */
    public function testOneLevelArrayDataNoKeepOrigin(): void
    {
        $expecteds_array_schema = $this->one_level_array_w_2_item_schema;
        $mock_json_data = json_decode($this->mock_1_level_json_data, true);

        $Destructor = new DestructureApiData();
        $Destructor->setOption('keep_origin', 0);

        // Set expected return schema;
        $Destructor->set('schema', $expecteds_array_schema);
        // Set API responsed data
        $Destructor->set('original_data', $mock_json_data);

        $my_api_data = $Destructor->destruct();

        // Expect 2 items in returned array
        $this->assertCount(2, $my_api_data, 'Test does not return 02 items');
    }

    /**
     * Test 1-level array data
     * 2/6 keys defined for schema
     * Keep original data
     */
    public function testOneLevelArrayDataKeepOrigin(): void
    {
        $expecteds_array_schema = $this->one_level_array_w_2_item_schema;
        $mock_json_data = json_decode($this->mock_1_level_json_data, true);

        $Destructor = new DestructureApiData();
        $Destructor->setOption('keep_origin', 1);

        // Set expected return schema;
        $Destructor->set('schema', $expecteds_array_schema);
        // Set API responsed data
        $Destructor->set('original_data', $mock_json_data);

        $my_api_data = $Destructor->destruct();

        // Expect 2 items in returned array
        $this->assertCount(6, $my_api_data, 'Test does not return 06 items');
    }

    /**
     * Test 1-level array data
     * 2/6 keys defined for schema
     * No keep original data
     */
    public function testKeyCorrectWithSchema(): void
    {
        $expecteds_array_schema = $this->one_level_array_w_2_item_schema;
        $mock_json_data = json_decode($this->mock_1_level_json_data, true);

        $Destructor = new DestructureApiData();
        $Destructor->setOption('keep_origin', 0);

        // Set expected return schema;
        $Destructor->set('schema', $expecteds_array_schema);
        // Set API responsed data
        $Destructor->set('original_data', $mock_json_data);

        $my_api_data = $Destructor->destruct();

        foreach ($expecteds_array_schema as $value) {
            $this->assertArrayHasKey($value, $my_api_data, 'Test does not have schema key ' . $value);
        }
    }

    /**
     * Test 1-level array data
     * 2/6 keys defined for schema
     * Keep original data
     */
    public function testKeyCorrectWithSchemaMixWithOrigin(): void
    {
        $expecteds_array_schema = $this->one_level_array_full_schema;
        $mock_json_data = json_decode($this->mock_1_level_json_data, true);

        $Destructor = new DestructureApiData();
        $Destructor->setOption('keep_origin', 1);

        // Set expected return schema;
        $Destructor->set('schema', $expecteds_array_schema);
        // Set API responsed data
        $Destructor->set('original_data', $mock_json_data);

        $my_api_data = $Destructor->destruct();

        $schema_keys = count($expecteds_array_schema);
        $match_count = 0;
        foreach ($expecteds_array_schema as $value) {
            if(array_key_exists($value, $my_api_data)) {
                $match_count++;
            }
        }

        // Expected number of keys in schema should be equal to actual count.
        $this->assertEquals($schema_keys, $match_count);
    }

    /**
     * Test 1-level array data
     * 6/6 keys defined for schema
     * No keep original data
     */
    public function testKeyCorrectWithFullSchema(): void
    {
        $expecteds_array_schema = $this->one_level_array_full_schema;
        $mock_json_data = json_decode($this->mock_1_level_json_data, true);

        $Destructor = new DestructureApiData();
        $Destructor->setOption('keep_origin', 0);

        // Set expected return schema;
        $Destructor->set('schema', $expecteds_array_schema);
        // Set API responsed data
        $Destructor->set('original_data', $mock_json_data);

        $my_api_data = $Destructor->destruct();

        foreach ($expecteds_array_schema as $value) {
            $this->assertArrayHasKey($value, $my_api_data, 'Test does not have schema key ' . $value);
        }
    }

    /**
     * Test 1-level array data
     * 6/6 keys defined for schema
     * No original data
     */
    public function testEverySchemaKeysReplaced(): void
    {
        $expecteds_array_schema = $this->one_level_array_full_schema;
        $mock_json_data = json_decode($this->mock_1_level_json_data, true);

        $Destructor = new DestructureApiData();
        $Destructor->setOption('keep_origin', 0);

        // Set expected return schema;
        $Destructor->set('schema', $expecteds_array_schema);
        // Set API responsed data
        $Destructor->set('original_data', $mock_json_data);

        $my_api_data = $Destructor->destruct();

        foreach ($my_api_data as $key => $value) {
            $this->assertContains($key, $expecteds_array_schema, 'Test does not have schema key ' . $value);
        }
    }

    /**
     * *************************************
     * ********** AGGRESSIVE TEST **********
     * *************************************
     */

    /**
     * Test 1-level array data
     * 2/6 keys defined for schema
     * Keep original data
     */
    // public function testServiceReturnAnArray(): void
    // {
    //     $expecteds_array_schema = $this->one_level_array_w_2_item_schema;
    //     $mock_json_data = json_decode($this->mock_1_level_json_data, true);

    //     $Destructor = new DestructureApiData();
    //     $Destructor->setOption('keep_origin', 0);

    //     // Set expected return schema;
    //     $Destructor->set('schema', $expecteds_array_schema);
    //     // Set API responsed data
    //     $Destructor->set('original_data', $mock_json_data);

    //     $my_api_data = $Destructor->destruct();

    //     $this->assertIsArray($my_api_data, 'Test does not receicve an array');
    // }

}
