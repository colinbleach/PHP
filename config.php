<?php
                 require '/opt/vendor/autoload.php';
                 use Aws\Common\Enum\Region;
                 use Aws\DynamoDb\Enum\Type;

                 $tableName = "person"; #Replace with your Database table name
                 $config = array(
                                 'key'    => 'AKIAIZAWSYM2S7OAB62Q',
                                 'secret' => 'Gtk8jJpcoaAS7H/zsv1DHYIPABccETafbDMGqxMF',
                                 'profile' => 'default',
                                 'region' => Region::US_WEST_2 #replace with your desired region     
                               );
              ?>