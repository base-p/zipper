<?php  
 echo '<!DOCTYPE html>
<html >
	<head>
        <title>page processor</title>
    </head>

    <body> ';
function print_r2($val){
        echo '<pre>';
        print_r($val);
        echo  '</pre>';
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_FILES['image']['name'])){
    print_r2($_FILES['image']['error']);
	$imagename =$_FILES['image']['name'];
	$permloc = "files/".$imagename;
	$temploc = $_FILES['image']['tmp_name'];
    $cssfiles = array();
    $jsfiles = array();
    $htmlfiles = array();
    $ids = array();
    $idcheck1 = array();
    $idcheck2 = array();
    $classes = array();
    $classcheck = array();
    $classcheck2 = array();
    $classcheck3 = array();
    $classcheck4 = array();
    $nodes = array();
    $rules[0]=$rules[1]=$rules[2]=array();
    $mediarules[0]=$mediarules[1]=array();
    $fontrules[0]=$fontrules[1]=$fontrules[2]=array();
    $finalrules=array();
    $finalmrules=array();
    $importrules[0]= $importrules[1]=array();
    $z=1;
    $y=1;
    $newname="";
	move_uploaded_file($temploc,$permloc);
    $folder = time().pathinfo($imagename)['filename'];
    $zip = new ZipArchive;
    error_reporting(E_ALL ^ E_WARNING); 
    if ($zip->open($permloc) === TRUE) {
        for($i = 0; $i < $zip->numFiles; $i++) {
          // echo '<p>'.$zip->getNameIndex($i).'</p>';
            $filedet = pathinfo($zip->getNameIndex($i));
            if(!empty($filedet['extension']) && $filedet['extension'] ==='html'){
                array_push($htmlfiles,$zip->getNameIndex($i));
                $zip->extractTo('unzipped/'.$folder, array($zip->getNameIndex($i)));
                $dom = new DOMDocument;
                $dom->loadHTMLFile( 'unzipped/'.$folder.'/'.$zip->getNameIndex($i));
                $dom->preserveWhiteSpace = false;
                $allno = $dom->getElementsByTagName('*');
                $xpath = new DOMXPath($dom);
                $allneo = $xpath->query("//link");
                foreach($allneo as $allni){$allni->parentNode->removeChild($allni);}
                
                $iaocss = $dom->createElement('link');
                $elm_type_attr = $dom->createAttribute('type');
                $elm_rel_attr = $dom->createAttribute('rel');
                $elm_href_attr = $dom->createAttribute('href');
                $elm_type_attr->value = 'text/css';
                $elm_rel_attr->value = 'stylesheet';
                $elm_href_attr->value = 'css/iao.css';
                $iaocss->appendChild($elm_type_attr);
                $iaocss->appendChild($elm_rel_attr);
                $iaocss->appendChild($elm_href_attr);
                $dhead=$dom->getElementsByTagName('head')->item(0);
                $dhead->appendChild($iaocss);
                
                foreach($allno as $alln){
                    if($alln->getAttribute('class')){
                        $alln->setAttribute('class', preg_replace("/ {2,}/", " ", $alln->getAttribute('class')));
                    }
                    if($alln->getAttribute('id') && !array_key_exists($alln->getAttribute('id'),$ids)){
                        $ids[$alln->getAttribute('id')] = 'iao'.$z;
                        $z++;
                    }
                    if(!in_array($alln->nodeName, $nodes)){
                        array_push($nodes, $alln->nodeName);
                    }
                    if($alln->getAttribute('class') && !array_key_exists($alln->getAttribute('class'),$classes)){
                        if ( preg_match('/\s/',$alln->getAttribute('class'))){
                            $classnames = explode(" ",$alln->getAttribute('class'));
                            foreach($classnames as $classname){
            
                                if(array_key_exists($classname,$classes)){
                                    $newname .= $classes[$classname]." ";
                                }elseif(array_key_exists($classname,$classcheck)){
                                    $newname .= $classcheck[$classname]." ";
                                }else{
                                    $newname .= 'iao'.$y." "; 
                                    $classcheck[$classname]= 'iao'.$y;
                                    $y++;                                    
                                }
                            }
                            $newname = substr($newname, 0, -1);
                            $classes[$alln->getAttribute('class')] = $newname;
                            $newname="";
                        }else{
                            if(array_key_exists($alln->getAttribute('class'),$classcheck)){
                                
                            }else{$classes[$alln->getAttribute('class')] = 'iao'.$y;
                            $classcheck2[$alln->getAttribute('class')] = 'iao'.$y;
                            $y++;}
                        }
                    }
                    if(array_key_exists($alln->getAttribute('id'),$ids)){
                        $alln->setAttribute('id', $ids[$alln->getAttribute('id')]);
                    }
                    if(array_key_exists($alln->getAttribute('class'),$classes)){
                        $alln->setAttribute('class', $classes[$alln->getAttribute('class')]);
                    }
                    
                    
                    
                   
                }
                //print_r2 ($classes);
                //print_r2 ($classcheck);
                //print_r2 ($ids);
                //print_r2 ($nodes);
                //$xpath = new DOMXPath( $dom );
               
                $dom->saveHTMLFile( 'unzipped/'.$folder.'/'.$zip->getNameIndex($i));
                
            }elseif(!empty($filedet['extension']) && $filedet['extension'] ==='css'){
                $zip->extractTo('unzipped/'.$folder, array($zip->getNameIndex($i)));
                array_push($cssfiles,$zip->getNameIndex($i));
                
                
            }elseif(!empty($filedet['extension']) && $filedet['extension'] ==='js'){
               $zip->extractTo('unzipped/'.$folder, array($zip->getNameIndex($i))); 
                array_push($jsfiles,$zip->getNameIndex($i));
            }else{$zip->extractTo('unzipped/'.$folder, array($zip->getNameIndex($i)));}                     
        // here you can run a custom function for the particular extracted file
                        
    }
        $zip->close();
        echo 'ok';
    } else {
        echo 'failed';
    }
    foreach($ids as $idsssk=>$idsssv){
        if(preg_match('/[-_]/',$idsssk)){
            $idcheck1[$idsssk] =$idsssv;
        }else{$idcheck2[$idsssk]=$idsssv;}
    }
    foreach($classcheck as $classss=>$classcheckt){
        if(preg_match('/[-_]/',$classss)){
            $classcheck3[$classss] =$classcheckt;
        }else{$classcheck4[$classss]=$classcheckt;}
    }
    
    foreach($classcheck2 as $classss2=>$classcheckt2){
        if(preg_match('/[-_]/',$classss2)){
            $classcheck3[$classss2]=$classcheckt2;
        }else{$classcheck4[$classss2]=$classcheckt2;}
    }
   echo '<h1> CSS FILES: </h1>';
    
    foreach($cssfiles as $cssfile){
        echo '<p>' .$cssfile.'</p>' ;
        $csspsd = file_get_contents('unzipped/'.$folder.'/'.$cssfile);
        unlink('unzipped/'.$folder.'/'.$cssfile);
                if(!empty($csspsd)){
                preg_match_all ("/((\r?\n)|(\r\n?))/", $csspsd, $med);
                $treatcss = str_replace(array("\n", "\r"), '', $csspsd);
                $treatcss=preg_replace('/>\s/','>', $treatcss);
                $treatcss=preg_replace('/\/\*(.+?)\*\//','', $treatcss);
                preg_match_all ("/\@import(.+?);/", $treatcss, $importq);
                preg_match_all ("/\@charset(.+?);/", $treatcss, $charsetq);
                    //print_r2($charsetq);
                    
                    for($i=0;$i<2;$i++){
                    $importrules[$i]=array_merge($importrules[$i],$importq[$i]);
                }
                array_merge($importrules,$importq);
                $treatcss=preg_replace('/\@import(.+?);/','', $treatcss);
                $treatcss=preg_replace('/\@charset(.+?);/','', $treatcss);
                    
                preg_match_all ("/\@(?!font|-ms-viewport)(.+?)\}}/", $treatcss, $mediaq);
                for($i=0;$i<2;$i++){
                    $mediarules[$i]=array_merge($mediarules[$i],$mediaq[$i]);
                }
                foreach($mediaq[0] as $mediaa){
                   $treatcss = str_replace($mediaa, '', $treatcss);
                    
                }
                preg_match_all ("/\@(.+?)\{(.+?)\}/", $treatcss, $fontq);
            
                for($i=0;$i<3;$i++){
                    $fontrules[$i]=array_merge($fontrules[$i],$fontq[$i]);
                }
                foreach($fontq[0] as $fontt){
                   $treatcss= str_replace($fontt, '', $treatcss);
                }
                $treatcss2 = '}'.$treatcss;
                preg_match_all ("/\}(.+?)\{(.+?)\}/", $treatcss, $otherq);
                preg_match_all ("/\}(.+?)\{(.+?)\}/", $treatcss2, $otherq2);
                //foreach(preg_split("/((\r?\n)|(\r\n?))/", $csspsd) as $line){
                   // $g++;
               // }
                //echo $g;
                //var_dump($otherq2);
                    $otherq3=[];
                foreach($otherq2 as $otherq2k=>$otherq2v){
                    
                    $newkey1=0;
                    $newkey2=1;
                    foreach($otherq2v as $otherq2ck=>$otherq2cv){
                       
                        $otherq3[$otherq2k][$newkey1]=$otherq2cv;
              
                        if(array_key_exists($otherq2ck,$otherq[$otherq2k])){
                            $otherq3[$otherq2k][$newkey2]=$otherq[$otherq2k][$otherq2ck];
                        }
                        $newkey1 = $newkey1 +2;
                        $newkey2 = $newkey2 +2;
                    }
                }
                
                for($i=0;$i<3;$i++){
                    $rules[$i]=array_merge($rules[$i],$otherq3[$i]);
                }
                $otherq3="";
                    //print_r2($otherq);                
                }
    }
    
    foreach($rules[0] as $sanitisek=>$sanitisev){
                    $rules[0][$sanitisek]=substr($sanitisev, 1);
                }
                //print_r2($rules[1]);
                //print_r2($ids);
                //print_r2($classes);
                //print_r2($classcheck);
    foreach($idcheck1 as $idsk=>$idsv){
        $idact = '#'.$idsk;
        $idactv = '#'.$idsv;
        foreach($rules[1] as $rulesaak=>$rulesaa){
            if (preg_match('/(^|[\s,\+\w])'.$idact.'[\s\{,\.#:>\+\[]/',$rulesaa)){
                $rules[0][$rulesaak]=preg_replace('/'.$idact.'/', $idactv, $rules[0][$rulesaak]);
                if(!in_array($rulesaak, $finalrules)){
                    array_push($finalrules, $rulesaak);
                }
            }
        }
        foreach($mediarules[0] as $mediarulesaak=>$mediarulesaa){
            if (preg_match('/(^|[\s,\+\w])'.$idact.'[\s\{,\.#:>\+\[]/',$mediarulesaa)){
                $mediarules[0][$mediarulesaak]=preg_replace('/'.$idact.'/', $idactv, $mediarules[0][$mediarulesaak]);
                if(!in_array($mediarulesaak, $finalmrules)){
                    array_push($finalmrules, $mediarulesaak);
                }
            }
        }
    }
    foreach($idcheck2 as $ids2k=>$ids2v){
        $idact2 = '#'.$ids2k;
        $idactv2 = '#'.$ids2v;
        foreach($rules[1] as $rulesaak=>$rulesaa){
            if (preg_match('/(^|[\s,\+\w])'.$idact2.'[\s\{,\.#:>\+\[]/',$rulesaa)){
                $rules[0][$rulesaak]=preg_replace('/'.$idact2.'/', $idactv2, $rules[0][$rulesaak]);
                if(!in_array($rulesaak, $finalrules)){
                    array_push($finalrules, $rulesaak);
                }
            }
        }
        foreach($mediarules[0] as $mediarulesaak=>$mediarulesaa){
            if (preg_match('/(^|[\s,\+\w\}])'.$idact2.'[\s\{,\.#:>\+\[]/',$mediarulesaa)){
                $mediarules[0][$mediarulesaak]=preg_replace('/'.$idact2.'/', $idactv2, $mediarules[0][$mediarulesaak]);
                if(!in_array($mediarulesaak, $finalmrules)){
                    array_push($finalmrules, $mediarulesaak);
                }
            }
        }
    }
    foreach($classcheck3 as $classcheckk=>$classcheckv){
        $classchkact = '\.'.$classcheckk;
        $classchkactv = '.'.$classcheckv;
        foreach($rules[1] as $rulesaak=>$rulesaa){
            if (preg_match('/(^|[\s,\+\w])'.$classchkact.'([\s\{,\.#:>\+\[]|$)/',$rulesaa)){
                $rules[0][$rulesaak]=preg_replace('/(^|[\s,\+\w])'.$classchkact.'([\s\{,\.#:>\+\[]|$)/', '$1'.$classchkactv.'$2', $rules[0][$rulesaak]);
                if(!in_array($rulesaak, $finalrules)){
                    array_push($finalrules, $rulesaak);
                }
            }
        }
        foreach($mediarules[0] as $mediarulesaak=>$mediarulesaa){
            if (preg_match('/(^|[\s,\+\w\}])'.$classchkact.'([\s\{,\.#:>\+\[]|$)/',$mediarulesaa)){
                $mediarules[0][$mediarulesaak]=preg_replace('/(^|[\s,\+\w\}])'.$classchkact.'([\s\{,\.#:>\+\[]|$)/', '$1'.$classchkactv.'$2', $mediarules[0][$mediarulesaak]);
                if(!in_array($mediarulesaak, $finalmrules)){
                    array_push($finalmrules, $mediarulesaak);
                }
            }
        }
        
    }
    foreach($classcheck4 as $classcheck2k=>$classcheck2v){
        $classchk2act = '\.'.$classcheck2k;
        $classchk2actv = '.'.$classcheck2v;
        foreach($rules[1] as $rulesaak=>$rulesaa){
            if (preg_match('/(^|[\s,\+\w])'.$classchk2act.'([\s\{,\.#:>\+\[]|$)/',$rulesaa)){
                echo $classchk2act;
                $rules[0][$rulesaak]=preg_replace('/(^|[\s,\+\w])'.$classchk2act.'([\s\{,\.#:>\+\[]|$)/', '$1'.$classchk2actv.'$2', $rules[0][$rulesaak]);
                if(!in_array($rulesaak, $finalrules)){
                    array_push($finalrules, $rulesaak);
                }
            }
        }
        foreach($mediarules[0] as $mediarulesaak=>$mediarulesaa){
            if (preg_match('/(^|[\s,\+\w\}])'.$classchk2act.'([\s\{,\.#:>\+\[]|$)/',$mediarulesaa)){
                $mediarules[0][$mediarulesaak]=preg_replace('/(^|[\s,\+\w\}])'.$classchk2act.'([\s\{,\.#:>\+\[]|$)/', '$1'.$classchk2actv.'$2', $mediarules[0][$mediarulesaak]);
                if(!in_array($mediarulesaak, $finalmrules)){
                    array_push($finalmrules, $mediarulesaak);
                }
            }
        }
    }
    foreach($nodes as $node){
        foreach($rules[1] as $rulesaak=>$rulesaa){
            if (preg_match('/(^|[\s\},>\+])'.$node.'[\s\{,\.#:>\+\[]/',$rulesaa)){
                if(!in_array($rulesaak, $finalrules)){
                    array_push($finalrules, $rulesaak);
                }
            }
        }
        foreach($mediarules[0] as $mediarulesaak=>$mediarulesaa){
            if (preg_match('/[\s\},>\+]'.$node.'[\s\{,\.#:>\+\[]/',$mediarulesaa)){
                if(!in_array($mediarulesaak, $finalmrules)){
                    array_push($finalmrules, $mediarulesaak);
                }
            }
        }
    }
    foreach($rules[0] as $rulesaak=>$rulesaa){
            if (preg_match('/\*[\s:\{,]/',$rulesaa)){
               if(!in_array($rulesaak, $finalrules)){
                    array_push($finalrules, $rulesaak);
                }
            }
        }
    foreach($mediarules[0] as $mediarulesaak=>$mediarulesaa){
            if (preg_match('/\@(?!media)(.+?)/',$mediarulesaa)){
                if(!in_array($mediarulesaak, $finalmrules)){
                    array_push($finalmrules, $mediarulesaak);
                }
            }
        }
    sort($finalmrules);
    sort($finalrules);
    sort($fontrules[0]);
   foreach($fontrules[0] as $fontrulef){
       file_put_contents('unzipped/'.$folder.'/css/iao.css', $fontrulef."\n", FILE_APPEND | LOCK_EX);
   }
    foreach($importrules[0] as $importqa){file_put_contents('unzipped/'.$folder.'/css/iao.css', $importqa."\n", FILE_APPEND | LOCK_EX);}
     foreach($finalmrules as $finalmrulek=>$finalmrulev){
        $csscontent = $mediarules[0][$finalmrulev];
        file_put_contents('unzipped/'.$folder.'/css/iao.css', $csscontent."\n", FILE_APPEND | LOCK_EX);
    }
    foreach($finalrules as $finalrulek=>$finalrulev){
        $csscontent = $rules[0][$finalrulev];
        file_put_contents('unzipped/'.$folder.'/css/iao.css', $csscontent."\n", FILE_APPEND | LOCK_EX);
    }
   
    //print_r2($ids);
    //print_r2($classes);
    //print_r2($classcheck3);
   //print_r2($classcheck4);
    //print_r2($mediarules);
    //print_r2($finalmrules);
    print_r2($rules[1]);
    //print_r2($importrules);
    
    //echo '<h1> JS FILES: </h1>';
    
    //foreach($jsfiles as $jsfile){echo '<p>' .$jsfile.'</p>' ;}
    
    //echo '<h1> HTML FILES: </h1>';
    
    //foreach($htmlfiles as $htmlfile){echo '<p>' .$htmlfile.'</p>' ;}
}
//     $ozip = new ZipArchive;
//    $rootPath = realpath('unzipped/'.$folder);
//    $ozip->open('zipped/'.$folder.'.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE);
//    $files = new RecursiveIteratorIterator(
//        new RecursiveDirectoryIterator($rootPath),
//        RecursiveIteratorIterator::LEAVES_ONLY
//    );
//    foreach ($files as $name => $file)
//{
//    // Skip directories (they would be added automatically)
//    if (!$file->isDir())
//    {
//        // Get real and relative path for current file
//        $filePath = $file->getRealPath();
//        $relativePath = substr($filePath, strlen($rootPath) + 1);
//
//        // Add current file to archive
//        $ozip->addFile($filePath, $relativePath);
//    }
//}
//     $ozip->close();
//    echo '<a href="zipped/'.$folder.'.zip">DOWNLOAD YOUR WEBPAGE AS ZIP</a>';

	echo '</body> </html>';


