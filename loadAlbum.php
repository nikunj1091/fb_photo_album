<?php
              $request = new FacebookRequest($session, 'GET', '/me/albums');
                $response = $request->execute();
                $graphObject = $response->getGraphObject();
                $albums = array();
                $albums = $graphObject->asArray('data');
                foreach ($albums["data"] as $obj) {
                    echo "<input type='checkbox' name='cb' value='" . $obj->id . "'/>  " . $obj->name . "<br/>";
                    $request1 = new FacebookRequest(
              $session,
              'GET',
              '/'.$obj -> id.'/photos'
            );
            $response1 = $request1->execute();
            $graphObject1 = $response1->getGraphObject();
            $data1 = array();
            $data1 = $graphObject1 -> asArray('data');
                    
                    if(!empty($data1))
                    {
                            foreach($data1['data'] as $photo)
                            {
                                     echo "<img src='{$photo->source}' /><br/>";
                                     break;
                            }
                }
                }
            ?>

            <!-- get album photos -->
            <!-- $request1 = new FacebookRequest(
              $session,
              'GET',
              '/'.$obj -> id.'/photos'
            );
            $response1 = $request1->execute();
            $graphObject1 = $response1->getGraphObject();
            $data1 = array();
            $data1 = $graphObject1 -> asArray('data');
                    
                    if(!empty($data1))
                    {
                            foreach($data1['data'] as $photo)
                    {
                        echo "<img src='{$photo->source}' /><br/>";
                    }
            <!-- } --> 


            <!-- 
            
            // $request1 = new FacebookRequest(
            // $session,
            // 'GET',
            // '/{.$obj -> id.}/picture'	
            // );
            // $response1 = $request1->execute();
            // $graphObject1 = $response1->getGraphObject();
            // $data1 = array();
            // $data1 = $graphObject1 -> asArray('data');
            
            // if(!empty($data1))
            // {
                            // var_dump($data1);
            //		foreach($data1['data'] as $photo)
            //      {
            //        echo "<img src='{$photo->source}' /><br/>";
            //  }
             }-->