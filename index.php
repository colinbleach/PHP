<?PHP
	require '/opt/vendor/autoload.php';

	  use Aws\DynamoDb\DynamoDbClient;
	  use Aws\Common\Enum\Region;
	  use Aws\DynamoDb\Enum\Type;
	  use Aws\DynamoDb\Enum\AttributeAction;
	  use Aws\DynamoDb\Enum\ReturnValue;
	  use Aws\DynamoDb\Enum\KeyType;
	  use Aws\S3\S3Client;
	  use Aws\Rds\RdsClient;
	  use Aws\ElasticBeanstalk\ElasticBeanstalkClient;

function setup(){
	$dynClient = DynamoDbClient::factory(array(
					'key' => 'AKIAIZAWSYM2S7OAB62Q',
					'secret' => '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',
				   'region'  => 'us-west-2',
					'endpoint' => 'http://dynamodb.us-west-2.amazonaws.com',
					'version' => 'latest'
				));
				
	$result = $dynClient->listTables();
	
	$exists = false;
	// TableNames contains an array of table names
	foreach ($result['TableNames'] as $tableName) {
		if($tableName == 'person')
		{
				$exists = true;
		}
	}
	
	if(!$exists){
		$dynClient->createTable(array(
			'TableName' => 'person',
			'AttributeDefinitions' => array(
				array(
					'AttributeName' => 'id',
					'AttributeType' => 'S'
				),
				array(
					'AttributeName' => 'data',
					'AttributeType' => 'S'
				)
			),
			'KeySchema' => array(
				array(
					'AttributeName' => 'id',
					'KeyType'       => 'HASH'
				),
				array(
					'AttributeName' => 'data',
					'KeyType'       => 'RANGE'
				)
			),
			'ProvisionedThroughput' => array(
				'ReadCapacityUnits'  => 1,
				'WriteCapacityUnits' => 1
			)
		));
		
		$dynClient->waitUntil('TableExists', array(
			'TableName' => 'person'
		));
	}
}

function teardown(){
	$dynClient = DynamoDbClient::factory(array(
					'key' => 'AKIAIZAWSYM2S7OAB62Q',
					'secret' => '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',
				   'region'  => 'us-west-2',
					'endpoint' => 'http://dynamodb.us-west-2.amazonaws.com',
					'version' => 'latest'
				));
				
	$dynClient->deleteTable(array(
    'TableName' => 'person'
	));

	$dynClient->waitUntil('TableNotExists', array(
		'TableName' => 'person'
	));
	
	$client = ElasticBeanstalkClient::factory(array(
					'key' => 'AKIAIZAWSYM2S7OAB62Q',
					'secret' => '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',
				   'region'  => 'us-west-2',
					'endpoint' => 'https://elasticbeanstalk.us-west-2.amazonaws.com'
				));
				
	$result = $client->terminateEnvironment(array(
		'EnvironmentName' => 'tradetracker',
		'TerminateResources' => true
	));
				
	return $client;
}

function takedown(){
	
	//$client = RdsClient::factory(array(
	//				'key' => 'AKIAIZAWSYM2S7OAB62Q',
	//				'secret' => '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',
	//			   'region'  => 'us-west-2',
	//				'endpoint' => 'https://rds.us-west-2.amazonaws.com'
	//			));
	
	//$result = $client->deleteDBInstance(array(
	//	#DBClusterIdentifier is required
	//	'DBClusterIdentifier' => 'aa1ceihdi49sdo6',
	//	'SkipFinalSnapshot' => true
	//));
	
	//$result = $client->deleteDBCluster(array(
	//	#DBClusterIdentifier is required
	//	'DBClusterIdentifier' => 'aa1ceihdi49sdo6',
	//	'SkipFinalSnapshot' => true
	//));
//return $result;
}

function test() {
	try{
       $data = array();

    $data[] = array(
            'First Name'   => 'test',
                        'Last Name' => 'test',
            'Age' => 10
        );

                $dbconn = pg_connect("host=aarfl7e46cylxt.cmai8v7xjrho.us-west-2.rds.amazonaws.com port=5432 dbname=postgres user=tradetracker password=TradeTrackerTest");
                $result = pg_query($dbconn, "INSERT INTO Person(FirstName, LastName, Age)
                  VALUES('test', 'test', 10);");

                //dump the result object
                var_dump($result);

                // Closing connection
                pg_close($dbconn);

    return $data;
	}catch (Exception $e) {
      return $e->getMessage() . "\n";
  }
}

function transfer(){
        $dbconn = pg_connect("host=aarfl7e46cylxt.cmai8v7xjrho.us-west-2.rds.amazonaws.com port=5432 dbname=postgres user=tradetracker password=TradeTrackerTest");
        $result = pg_query($dbconn, "SELECT * FROM Person");
        $arr = pg_fetch_all($result);

       foreach($arr as $val) {
               $client = DynamoDbClient::factory(array(
					'key' => 'AKIAIZAWSYM2S7OAB62Q',
					'secret' => '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',
				   'region'  => 'us-west-2',
					'endpoint' => 'http://dynamodb.us-west-2.amazonaws.com',
					'version' => 'latest'
				));
		$id = getGUID();
        $response = $client->putItem(array(
			   'TableName' => 'person',
					 'Item' => array('id' => array('S' => $id),
						'data' => array('S' => '{"firstname":"test","lastname":"test","age":10}')
						)
			));
		
       }

        //dump the result object
        var_dump($result);

        // Closing connection
        pg_close($dbconn);

        return $result;
}

function countRows(){
try{

	$client = DynamoDbClient::factory(array(
		'key' => 'AKIAIZAWSYM2S7OAB62Q',
		'secret' => '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',
	   'region'  => 'us-west-2',
		'endpoint' => 'http://dynamodb.us-west-2.amazonaws.com',
		'version' => 'latest'
	));

	#        $response = $client->putItem(array(
	#                               'TableName' => 'person',
	#                                     'Item' => array('id' => array('S' => '9bc6fcd5-34b0-4833-9e00-59481cfd4405'),
	#                                        'data' => array('S' => '{"firstname":"test","lastname":"test","age":10}')
	#)
	#));

	#$result = $client->describeTable(array(
	#    'TableName' => 'person'
	#));

	$iterator = $client->getIterator('Scan', array(
		'TableName' => 'person'));
	$i = 0;
	foreach ($iterator as $item) {
	   $i++;
	}
	return "Count: ".$i;

  }
  catch (Exception $e) {
      return $e->getMessage() . "\n";
  }
}

function getGUID(){
    if (function_exists('com_create_guid')){
        return com_create_guid();
    }else{
        mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45);// "-"
        $uuid = chr(123)// "{"
            .substr($charid, 0, 8).$hyphen
            .substr($charid, 8, 4).$hyphen
            .substr($charid,12, 4).$hyphen
            .substr($charid,16, 4).$hyphen
            .substr($charid,20,12)
            .chr(125);// "}"
        return $uuid;
    }
}


$value = "An error has occurred";

$possible_url = array("test","transfer","count","takedown","teardown");

if (isset($_GET["action"]) && in_array($_GET["action"], $possible_url))
{
  switch ($_GET["action"])
    {
      case "test":
        $value = test();
        break;
      case "transfer":
        $value = transfer();
        break;
      case "count":
        $value = countRows();
        break;
	  case "takedown":
        $value = takedown();
        break;
	  case "teardown":
        $value = teardown();
        break;
    }
}

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
		$value = setup();
}

exit(json_encode($value));
?>
