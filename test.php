<?PHP
	require '/opt/vendor/autoload.php';
	  require '/opt/config.php';

	  use Aws\DynamoDb\DynamoDbClient;
	  use Aws\Common\Enum\Region;
	  use Aws\DynamoDb\Enum\Type;
	  use Aws\DynamoDb\Enum\AttributeAction;
	  use Aws\DynamoDb\Enum\ReturnValue;
	  use Aws\DynamoDb\Enum\KeyType;
	  use Aws\S3\S3Client;
	  use Aws\Rds\RdsClient;

function takedown(){
	$client = RdsClient::factory(array(
					'key' => 'AKIAIZAWSYM2S7OAB62Q',
					'secret' => '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',
				   'region'  => 'us-west-2',
					'endpoint' => 'http://dynamodb.us-west-2.amazonaws.com',
					'version' => 'latest'
				));
	
	 $result = $client->deleteDBInstance(array(
    // DBClusterIdentifier is required
    'DBClusterIdentifier' => 'aa1ceihdi49sdo6',
    'SkipFinalSnapshot' => true
));
}

function test() {

       $data = array();

    $data[] = array(
            'First Name'   => 'test',
                        'Last Name' => 'test',
            'Age' => 10
        );

                $dbconn = pg_connect("host=aa1ceihdi49sdo6.cmai8v7xjrho.us-west-2.rds.amazonaws.com port=5432 dbname=postgres user=tradetracker password=TradeTrackerTest");
                $result = pg_query($dbconn, "INSERT INTO Person(FirstName, LastName, Age)
                  VALUES('test', 'test', 10);");

                //dump the result object
                var_dump($result);

                // Closing connection
                pg_close($dbconn);

    return $data;
}

function transfer(){
        $dbconn = pg_connect("host=aa1ceihdi49sdo6.cmai8v7xjrho.us-west-2.rds.amazonaws.com port=5432 dbname=postgres user=tradetracker password=TradeTrackerTest");
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
			
        $response = $client->putItem(array(
			   'TableName' => 'person',
					 'Item' => array('id' => array('S' => '9bc6fcd5-34b0-4833-9e00-59481cfd4405'),
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

$data = array();

    $data[] = array(
            'First Name'   => 'test',
                        'Last Name' => 'test',
            'Age' => 10
        );

                $dbconn = pg_connect("host=aa1ceihdi49sdo6.cmai8v7xjrho.us-west-2.rds.amazonaws.com port=5432 dbname=postgres user=tradetracker password=TradeTrackerTest");
                $result = pg_query($dbconn, "INSERT INTO Person(FirstName, LastName, Age)
                  VALUES('test', 'test', 10);");

                //dump the result object
                var_dump($result);

                // Closing connection
                pg_close($dbconn);


exit(json_encode($data));
?>
