<?php
                if(isset($session))
                {
                $user_profile = new FacebookRequest($session, 'GET', '/me');
                $response = $user_profile->execute();
                $user_profile_graphObject = $response->getGraphObject();
                echo $user_profile_graphObject->getProperty('name');
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
            // }-->
