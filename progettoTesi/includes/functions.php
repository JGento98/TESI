<?php
/*
	function pretty_print($arr){
		echo "<pre>";
		print_r($arr);
		echo "</pre>";
	}
*/
/* * * * * * * * * * * * * * *
*		BALISAGE
* * * * * * * * * * * * * * */
	require_once( ROOT_PATH . '/includes/balisageScrape.php');
		//recupera tutti gli articoli flaggati P (proceedings)
		function getBalisageProceedings() {
			global $conn;
			
			$sql = "SELECT * FROM balisage_art WHERE b_type='P' ";
			$result = mysqli_query($conn, $sql);

			$articles = mysqli_fetch_all($result, MYSQLI_ASSOC);

			return $articles;
		}
		function getTopic() {
			global $conn;
			$sql = "SELECT DISTINCT b_art_topic FROM balisage_art WHERE b_type='T' ";
			$result = mysqli_query($conn, $sql);
			$topic = mysqli_fetch_all($result, MYSQLI_ASSOC);
			return $topic;
		}
		function getVolume() {
			global $conn;
			$sql = "SELECT DISTINCT b_art_volume FROM balisage_art WHERE b_type='P' ";
			$result = mysqli_query($conn, $sql);
			$vol = mysqli_fetch_all($result, MYSQLI_ASSOC);
			return $vol;
		}
		function getYear() {
			global $conn;
			$sql = "SELECT DISTINCT b_art_year FROM balisage_art  ";
			$result = mysqli_query($conn, $sql);
			$year = mysqli_fetch_all($result, MYSQLI_ASSOC);
			return $year;
		}
		function filtroAvanzato($post){
			global $conn;
			//pretty_print($post);
			$tipo = '"'.$_GET['tiporicerca'].'"';
			if(!empty($post['autore'])){
				$autore = $post['autore'];
				$AND_aut = "AND b_art_author LIKE '%$autore%' ";
			}else{
				$AND_aut = '';
				}
			if(!empty($post['titolo'])){
				$titolo = $post['titolo'];
				$AND_tit = "AND b_art_title LIKE '%$titolo%' ";
			}else{
				$AND_tit = '';
			}

			if(!empty($post['annoDA'])){$annoDA = $post['annoDA'];}else{$annoDA = 0;}
			if(!empty($post['annoA'])) {$annoA = $post['annoA'];}else{$annoA = 0;}

			if(!empty($post['anno'])){
				$AND_year = 'AND (';
				$count = count($post['anno'])-1;
				foreach($post['anno'] as $k => $anno){
					if($k < $count){
						$AND_year .= 'b_art_year = '.$anno.' OR ';
					}else if($k == $count){
						$AND_year .= 'b_art_year = '.$anno.' ';
					}	
				}
				$AND_year .= ')';
			}else{
				$AND_year = '';
			}

			if(!empty($post['volume'])){
				$AND_volume = 'AND (';
				$count = count($post['volume'])-1;
				foreach($post['volume'] as $k => $volume){
					if($k < $count){
						$AND_volume .= 'b_art_volume = "'.$volume.'" OR ';
					}else if($k == $count){
						$AND_volume .= 'b_art_volume = "'.$volume.'" ';
					}	
				}
				$AND_volume .= ')';
			}else{
				$AND_volume = '';
			}

			if(!empty($post['topic'])){
				$AND_topic = 'AND (';
				$count = count($post['topic'])-1;
				foreach($post['topic'] as $k => $topic){
					if($k < $count){
						$AND_topic .= 'b_art_topic = "'.$topic.'" OR ';
					}else if($k == $count){
						$AND_topic .= 'b_art_topic = "'.$topic.'" ';
					}	
				}
				$AND_topic .= ')';
			}else{
				$AND_topic = '';
			}
			if( $annoDA && $annoA){
				//$AND_DA_A = "AND b_art_year BETWEEN $annoDA AND $annoA ";	
				if( $annoA < $annoDA){
					$AND_DA_A = "AND b_art_year BETWEEN $annoA AND $annoDA ";
				}else{
					$AND_DA_A = "AND b_art_year BETWEEN $annoDA AND $annoA ";
				}
					
				
			}else{
				$AND_DA_A = '';
			}

			$sql = "SELECT * 
			FROM balisage_art 
			WHERE 1 = 1
			AND b_type = $tipo
			$AND_aut
			$AND_tit
			$AND_year
			$AND_volume
			$AND_topic
			$AND_DA_A	
			";
			//pretty_print($sql);
			$result = mysqli_query($conn, $sql);

			$articles = mysqli_fetch_all($result, MYSQLI_ASSOC);
			//pretty_print($articles);
			return $articles;

		}
		//elimina i proceedings
		function deleteAllProceedings(){
			global $conn;
			
			$sql = "SELECT b_art_id FROM `balisage_art` WHERE 1=1";
			$result = mysqli_query($conn, $sql);

			$count = mysqli_fetch_all($result, MYSQLI_ASSOC);
			if(!empty($count)){
				foreach($count as $k => $val){
					$arr[] = $val['b_art_id'];
				}

				foreach($arr as $key => $id){
					$delete_sql = "DELETE FROM balisage_art where b_art_id = $id AND b_type = 'P' ";
					$result = mysqli_query($conn, $delete_sql);
				}
			}
		}
		//recupera tutti gli articoli flaggati T (topics)
		function getBalisageTopics() {	
			global $conn;
			$sql = "SELECT * FROM balisage_art WHERE b_type='T' ";;
			$result = mysqli_query($conn, $sql);
		
			$articles = mysqli_fetch_all($result, MYSQLI_ASSOC);

			return $articles;
		}
		//elimina i topic
		function deleteAlltopics(){
			global $conn;
			
			$sql = "SELECT b_art_id FROM `balisage_art` WHERE 1=1";
			$result = mysqli_query($conn, $sql);
			
			$count = mysqli_fetch_all($result, MYSQLI_ASSOC);
			if(!empty($count)){
				foreach($count as $k => $val){
					$arr[] = $val['b_art_id'];
				}

				foreach($arr as $key => $id){
					$delete_sql = "DELETE FROM balisage_art where b_art_id = $id AND b_type = 'T' ";
					$result = mysqli_query($conn, $delete_sql);
				}
			}

		}	
		//AGGIUNGONO proceedings e topic 
		function addProceedings(){
			global $conn;
				$urlProceedings = 'https://www.balisage.net/Proceedings/index.html';
				$proceedings = SCRAPEALLPROCEEDINGS($urlProceedings);
				$arr = [];
				$i=0;
				foreach($proceedings as $k =>$val_arr){
					foreach($val_arr as $key =>$val){
						$arr[$i]['title'] 	= trim($val['title']);
						$arr[$i]['link'] 	= trim($val['link']);
						$arr[$i]['volume'] 	= trim($val['volume']);
						$arr[$i]['year']	= trim($val['year']);
						$arr[$i]['author']	= trim($val['author']);
						$i++;
					}
				}	
					$counter = 0;
					$len= count($arr);

					foreach($arr as $key =>$val){
						$title 	= trim($val['title']);
						$link	= trim($val['link']);
						$vol	= trim($val['volume']);
						$year	= trim($val['year']);
						$author	= trim($val['author']);

					if( $counter < $len){
					/*vecchia query su tab divise 								
					$query = "INSERT INTO balisage_proceedings 
					(b_proceedings_art_id, b_proceedings_art_title, b_proceedings_art_link, b_proceedings_art_volume, b_proceedings_art_year) 
					VALUES('', '$title', '$link','$vol','$year')";
					*/
					$query = "INSERT INTO balisage_art 
					(b_art_id, b_art_title, b_art_author, b_art_link, b_art_volume, b_art_year, b_art_topic, b_type,b_table_id ) 
					VALUES('','$title', '$author' ,'$link','$vol','$year',null,'P',1 )";
					mysqli_query($conn, $query);
					$counter++;
						}else{
							break;
						}
				
					}  					
		}
			//NON ESEGUE LA QUERY SE METTO  VOL E YEAR  
		function addTopics(){
			global $conn;
				$balisageTopicsURL     = 'https://www.balisage.net/Proceedings/topics.html';
				$BalisageTopics        = SCRAPEOFTOPICSBALISAGE($balisageTopicsURL);
				
				$arr = [];
				$i=0;
				foreach($BalisageTopics as $k =>$val_arr){
					foreach($val_arr as $key =>$val){
						$arr[$i]['title'] = $val['title'];
						$arr[$i]['link'] = $val['link'];
						$arr[$i]['topic'] = $val['topic'];
						$arr[$i]['author'] = $val['author'];
						$arr[$i]['year'] = $val['year'];
						$arr[$i]['volume'] = $val['volume'];
						
						$i++;
					}
				}	
					
					
					foreach($arr as $key =>$val){
						$title 	= $val['title'];
						$link	= $val['link'];
						$topic	= $val['topic'];
						$author = $val['author'];
						$year = $val['year'];
						$volume = $val['volume'];
					
					$query = "INSERT INTO balisage_art 
					(b_art_id, b_art_title, b_art_author, b_art_link, b_art_volume, b_art_year, b_art_topic, b_type,b_table_id ) 
					VALUES('','$title', '$author' ,'$link','$volume','$year','$topic','T',1 )";
					/*
						$query = "INSERT INTO balisage_topics (b_topics_art_id, b_topics_art_title, b_topics_art_link, b_topics_art_topic) 
						VALUES('', '$title', '$link','$topic')";
						
					*/
					mysqli_query($conn, $query);
						
				
					}  					
		}

/* * * * * * * * * * * * * * *
*		DSH
* * * * * * * * * * * * * * */
	require_once( ROOT_PATH . '/includes/DSHScrape.php');
	function getAllIssues(){
		global $conn;
		$sql = "SELECT * FROM DSH_art WHERE dsh_type ='IS' ";;
		$result = mysqli_query($conn, $sql);

		$articles = mysqli_fetch_all($result, MYSQLI_ASSOC);

		return $articles;
	}
	function get_advanced_articles(){
		global $conn;
		$sql = "SELECT * FROM DSH_art WHERE dsh_type ='AD' ";;
		$result = mysqli_query($conn, $sql);
		$articles = mysqli_fetch_all($result, MYSQLI_ASSOC);
		return $articles;
	}
	function getVolumeDSH() {
		global $conn;
		$sql = "SELECT DISTINCT dsh_art_volume FROM DSH_art WHERE dsh_type='IS' ";
		$result = mysqli_query($conn, $sql);
		$vol = mysqli_fetch_all($result, MYSQLI_ASSOC);
		return $vol;
	}
	function getYearDSH() {
		global $conn;
		$sql = "SELECT DISTINCT dsh_art_year FROM DSH_art  ";
		$result = mysqli_query($conn, $sql);
		$year = mysqli_fetch_all($result, MYSQLI_ASSOC);
		return $year;
	}
	function add_all_Issues(){
		global $conn;
			$all_issues_archive = 'https://academic.oup.com/dsh/issue-archive';
			$All_issues2015to2020 = DOSCRAPEOFALLISSUE($all_issues_archive);

			$arr = [];
			$i=0;
			foreach($All_issues2015to2020 as $k =>$val_arr){
				foreach($val_arr as $key =>$val){
					$arr[$i]['title'] 	= $val['title'];
					$arr[$i]['link'] 	= $val['link'];
					$arr[$i]['volume'] 	= $val['volume'];
					$arr[$i]['year']	= $val['year'];
					$arr[$i]['authors']	= $val['authors'];
					$i++;
				}
			}	
				$counter = 0;
				$len= count($arr);

				foreach($arr as $key =>$val){
					$title 	= $val['title'];
					$link	= $val['link'];
					$vol	= $val['volume'];
					$year	= $val['year'];
					$author = $val['authors'];

					if( $counter < $len){
						$query = "INSERT INTO dsh_art (dsh_art_id, dsh_art_title, dsh_art_author, dsh_art_link, dsh_art_volume, dsh_art_year, dsh_type,dsh_table_id) 
						VALUES('', '$title', '$author','$link','$vol','$year','IS',2)";
					/*	
						$query = "INSERT INTO dsh_issues (dsh_issues_art_id, dsh_issuesart_title, dsh_issues_art_link, dsh_issues_art_volume, dsh_issues_art_year) 
						VALUES('', '$title', '$link','$vol','$year')";
					*/
						mysqli_query($conn, $query);
						$counter++;
					}else{
						break;
					}
			
				}  					
	}
	function add_advance_articles(){
		global $conn;
			$dshURL   = 'https://academic.oup.com/dsh/advance-articles';
			$advance_articles = scrapeDSH_advance_articles($dshURL);
			
				foreach($advance_articles as $key =>$val){
					$title 	= $val['title'];
					$link	= $val['link'];
					$year	= $val['year'];
					$authors= $val['authors'];

				$query = "INSERT INTO dsh_art (dsh_art_id, dsh_art_title, dsh_art_author, dsh_art_link, dsh_art_volume, dsh_art_year, dsh_type,dsh_table_id) 
					VALUES('', '$title', '$authors','$link',null,'$year','AD',2)";
				mysqli_query($conn, $query);

				}  					
	}
	function delete_advance_articles(){
		global $conn;
		$sql = "SELECT dsh_art_id FROM `DSH_art`";
		$result = mysqli_query($conn, $sql);

		$count = mysqli_fetch_all($result, MYSQLI_ASSOC);
		if(!empty($count)){
			foreach($count as $k => $val){
				$arr[] = $val['dsh_art_id'];
			}

			foreach($arr as $key => $id){
				$delete_sql = "DELETE FROM DSH_art where dsh_art_id = $id AND dsh_type ='AD'";
				$result = mysqli_query($conn, $delete_sql);
			}
		}
	}
	function delete_all_Issues(){
		global $conn;
		$sql = "SELECT dsh_art_id FROM `DSH_art` WHERE dsh_type ='IS'";
		$result = mysqli_query($conn, $sql);

		$count = mysqli_fetch_all($result, MYSQLI_ASSOC);
		if(!empty($count)){
			foreach($count as $k => $val){
				$arr[] = $val['dsh_art_id'];
			}

			foreach($arr as $key => $id){
				$delete_sql = "DELETE FROM DSH_art where dsh_art_id = $id AND dsh_type ='IS'";
				$result = mysqli_query($conn, $delete_sql);
			}
		}
	
	}
	function filtroAvanzatoDSH($post){
		global $conn;
		$tipo = '"'.$_GET['tiporicerca'].'"';
		if(!empty($post['autore'])){
			$autore = $post['autore'];
			$AND_aut = "AND dsh_art_author LIKE '%$autore%' ";
		}else{
			$AND_aut = '';
			}
		if(!empty($post['titolo'])){
			$titolo = $post['titolo'];
			$AND_tit = "AND dsh_art_title LIKE '%$titolo%' ";
		}else{
			$AND_tit = '';
		}

		if(!empty($post['annoDA'])){$annoDA = $post['annoDA'];}else{$annoDA = 0;}
		if(!empty($post['annoA'])) {$annoA = $post['annoA'];}else{$annoA = 0;}

		if(!empty($post['anno'])){
			$AND_year = 'AND (';
			$count = count($post['anno'])-1;
			foreach($post['anno'] as $k => $anno){
				if($k < $count){
					$AND_year .= 'dsh_art_year = '.$anno.' OR ';
				}else if($k == $count){
					$AND_year .= 'dsh_art_year = '.$anno.' ';
				}	
			}
			$AND_year .= ')';
		}else{
			$AND_year = '';
		}

		if(!empty($post['volume'])){
			$AND_volume = 'AND (';
			$count = count($post['volume'])-1;
			foreach($post['volume'] as $k => $volume){
				if($k < $count){
					$AND_volume .= 'dsh_art_volume = "'.$volume.'" OR ';
				}else if($k == $count){
					$AND_volume .= 'dsh_art_volume = "'.$volume.'" ';
				}	
			}
			$AND_volume .= ')';
		}else{
			$AND_volume = '';
		}
		if( $annoDA && $annoA){
			//$AND_DA_A = "AND b_art_year BETWEEN $annoDA AND $annoA ";	
			if( $annoA < $annoDA){
				$AND_DA_A = "AND dsh_art_year BETWEEN $annoA AND $annoDA ";
			}else{
				$AND_DA_A = "AND dsh_art_year BETWEEN $annoDA AND $annoA ";
			}
				
			
		}else{
			$AND_DA_A = '';
		}

		$sql = "SELECT * 
				FROM DSH_art 
				WHERE 1 = 1
				AND dsh_type = $tipo
				$AND_aut
				$AND_tit
				$AND_year
				$AND_volume
				$AND_DA_A	
		";
		$result = mysqli_query($conn, $sql);
		$articles = mysqli_fetch_all($result, MYSQLI_ASSOC);
		//pretty_print($articles);
		return $articles;

	}

/* * * * * * * * * * * * * * *
*		JTEI
* * * * * * * * * * * * * * */
	require_once( ROOT_PATH . '/includes/JTEIscrape.php');
	function get_JTEI_Issues(){
		global $conn;
		$sql = "SELECT * FROM jtei_art ";;
		$result = mysqli_query($conn, $sql);

		$articles = mysqli_fetch_all($result, MYSQLI_ASSOC);

		return $articles;
	}
	function add_all_JTEI_Issues(){
		global $conn;
			$jteiURL     = 'https://journals.openedition.org/jtei/124';
			$All_issues = scrapeAll($jteiURL);
		
			$arr = [];
			$i=0;
			foreach($All_issues as $k =>$val_arr){
				foreach($val_arr as $key =>$val){
					$arr[$i]['title'] 	= $val['title'];
					$arr[$i]['link'] 	= $val['link'];
					$arr[$i]['year']	= $val['year'];
					$arr[$i]['author']	= $val['author'];
					$i++;
				}
			}	
				$counter = 0;
				$len= count($arr);
				
				foreach($arr as $key =>$val){
					$title 	= $val['title'];
					$link	= $val['link'];
					$year	= $val['year'];
					$author = $val['author'];

					if( $counter < $len){
						$query = "INSERT INTO jtei_art (jtei_art_id, jtei_art_title, jtei_art_author, jtei_art_link, jtei_art_year,jtei_table_id) 
						VALUES('', '$title', '$author','$link','$year',3)";
						
						mysqli_query($conn, $query);
						$counter++;
					}else{
						break;
					}
			
				}  					
	}
	function delete_JTEI_Issues(){
		global $conn;
		$sql = "SELECT jtei_art_id FROM `jtei_art`";
		$result = mysqli_query($conn, $sql);

		$count = mysqli_fetch_all($result, MYSQLI_ASSOC);
		if(!empty($count)){
			foreach($count as $k => $val){
				$arr[] = $val['jtei_art_id'];
			}

			foreach($arr as $key => $id){
				$delete_sql = "DELETE FROM jtei_art where jtei_art_id = $id ";
				$result = mysqli_query($conn, $delete_sql);
			}
		}

	}
	function filtroAvanzatoJTEI($post){
		global $conn;
		$tipo = '"'.$_GET['tiporicerca'].'"';
		if(!empty($post['autore'])){
			$autore = $post['autore'];
			$AND_aut = "AND jtei_art_author LIKE '%$autore%' ";
		}else{
			$AND_aut = '';
			}
		if(!empty($post['titolo'])){
			$titolo = $post['titolo'];
			$AND_tit = "AND jtei_art_title LIKE '%$titolo%' ";
		}else{
			$AND_tit = '';
		}

		if(!empty($post['annoDA'])){$annoDA = $post['annoDA'];}else{$annoDA = 0;}
		if(!empty($post['annoA'])) {$annoA = $post['annoA'];}else{$annoA = 0;}

		if(!empty($post['anno'])){
			$AND_year = 'AND (';
			$count = count($post['anno'])-1;
			foreach($post['anno'] as $k => $anno){
				if($k < $count){
					$AND_year .= 'jtei_art_year = '.$anno.' OR ';
				}else if($k == $count){
					$AND_year .= 'jtei_art_year = '.$anno.' ';
				}	
			}
			$AND_year .= ')';
		}else{
			$AND_year = '';
		}
		if( $annoDA && $annoA){
			//$AND_DA_A = "AND b_art_year BETWEEN $annoDA AND $annoA ";	
			if( $annoA < $annoDA){
				$AND_DA_A = "AND jtei_art_year BETWEEN $annoA AND $annoDA ";
			}else{
				$AND_DA_A = "AND jtei_art_year BETWEEN $annoDA AND $annoA ";
			}
		}else{
			$AND_DA_A = '';
		}

		$sql = "SELECT * 
				FROM jtei_art 
				WHERE 1 = 1
				$AND_aut
				$AND_tit
				$AND_year
				$AND_DA_A	
		";
		
		$result = mysqli_query($conn, $sql);
		$articles = mysqli_fetch_all($result, MYSQLI_ASSOC);
		//pretty_print($articles);
		return $articles;

	}
	function getYearJTEI() {
		global $conn;
		$sql = "SELECT DISTINCT jtei_art_year FROM jtei_art  ";
		$result = mysqli_query($conn, $sql);
		$year = mysqli_fetch_all($result, MYSQLI_ASSOC);
		return $year;
	}

/* * * * * * * * * * * * * * *
*		GOOGLE SCHOOLAR
* * * * * * * * * * * * * * */
	require_once( ROOT_PATH . '/includes/GscholarScrape.php');
	
	function add_10Pages_of_articles_GoogleScholar(){
		global $conn;
		$pages_arr= [
			'https://scholar.google.com/scholar?hl=it&as_sdt=0,5&q=digital+scholarly+editing',
			'https://scholar.google.com/scholar?start=10&q=digital+scholarly+editing&hl=it&as_sdt=0,5',
			'https://scholar.google.com/scholar?start=20&q=digital+scholarly+editing&hl=it&as_sdt=0,5',
			'https://scholar.google.com/scholar?start=30&q=digital+scholarly+editing&hl=it&as_sdt=0,5',
			'https://scholar.google.com/scholar?start=40&q=digital+scholarly+editing&hl=it&as_sdt=0,5',
			'https://scholar.google.com/scholar?start=50&q=digital+scholarly+editing&hl=it&as_sdt=0,5',
			'https://scholar.google.com/scholar?start=60&q=digital+scholarly+editing&hl=it&as_sdt=0,5',
			'https://scholar.google.com/scholar?start=70&q=digital+scholarly+editing&hl=it&as_sdt=0,5',
			'https://scholar.google.com/scholar?start=80&q=digital+scholarly+editing&hl=it&as_sdt=0,5',
			'https://scholar.google.com/scholar?start=90&q=digital+scholarly+editing&hl=it&as_sdt=0,5',
			];
	
		
		//$pages_arr = scrapeGooglePage($url);
		$articles = DOSCRAPEOFALLARTICLE($pages_arr);

		$arr = [];
		$i=0;
		foreach($articles as $k =>$val_arr){
			foreach($val_arr as $key =>$val){
				$arr[$i]['title'] 		= $val['title'];
				$arr[$i]['link'] 		= $val['link'];
				$arr[$i]['year'] 		= $val['year'];
				$arr[$i]['authors']     = $val['authors'];
				//$arr[$i]['numCites'] 	= $val['number_of_cites'];
				$i++;
			}
		}	
			$counter = 0;
			$len= count($arr);

			foreach($arr as $key =>$val){
				$title 		= $val['title'];
				$link		= $val['link'];
				$year		= $val['year'];
				$author     = $val['authors'];
				//$numCities	= $val['numCites'];
				

				if( $counter < $len){
					$query = "INSERT INTO google_scholar_art (GS_art_id, GS_art_title, GS_art_link, GS_art_year, GS_art_author,GS_table_id) 
						VALUES('', '$title', '$link','$year','$author',4)";
					mysqli_query($conn, $query);
					$counter++;
				}else{
					break;
				}
		
			}  					
			
	}
	function add_10articles($url){
		global $conn;
		$articles = scrapeGoogleScholar($url);
		
		$arr = [];
		$i=0;
		foreach($articles as $k =>$val){
			//foreach($val_arr as $key =>$val){
				$arr[$i]['title'] 		= $val['title'];
				$arr[$i]['link'] 		= $val['link'];
				$arr[$i]['year'] 		= $val['year'];
				$arr[$i]['authors']     = $val['authors'];
				//$arr[$i]['numCites'] 	= $val['number_of_cites'];
				$i++;
			//}
		}	
			$counter = 0;
			$len= count($arr);

			foreach($arr as $key =>$val){
				$title 		= $val['title'];
				$link		= $val['link'];
				$year		= $val['year'];
				$author     = $val['authors'];
				//$numCities	= $val['numCites'];

				if( $counter < $len){
					$query = "INSERT INTO google_scholar_art (GS_art_id, GS_art_title, GS_art_link, GS_art_year, GS_art_author,GS_table_id) 
						VALUES('', '$title', '$link','$year','$author',4)";
					mysqli_query($conn, $query);
					$counter++;
				}else{
					break;
				}
		
			}  					
			
	}

	function getAllGoogleArticles(){
		global $conn;
		$sql = "SELECT * FROM google_scholar_art";;
		$result = mysqli_query($conn, $sql);
		$articles = mysqli_fetch_all($result, MYSQLI_ASSOC);

		return $articles;
	}
	function delete_all_GS(){
		global $conn;
		$sql = "SELECT GS_art_id FROM `google_scholar_art` WHERE 1=1";
		$result = mysqli_query($conn, $sql);
		$count = mysqli_fetch_all($result, MYSQLI_ASSOC);
		if(!empty($count)){
			foreach($count as $k => $val){
				$arr[] = $val['GS_art_id'];
			}

			foreach($arr as $key => $id){
				$delete_sql = "DELETE FROM google_scholar_art where GS_art_id = $id";
				$result = mysqli_query($conn, $delete_sql);
			}
		}
	}
	function filtroAvanzatoGS($post){
		global $conn;
		if(!empty($post['autore'])){
			$autore = $post['autore'];
			$AND_aut = "AND GS_art_author LIKE '%$autore%' ";
		}else{
			$AND_aut = '';
			}
		if(!empty($post['titolo'])){
			$titolo = $post['titolo'];
			$AND_tit = "AND GS_art_title LIKE '%$titolo%' ";
		}else{
			$AND_tit = '';
		}

		if(!empty($post['annoDA'])){$annoDA = $post['annoDA'];}else{$annoDA = 0;}
		if(!empty($post['annoA'])) {$annoA = $post['annoA'];}else{$annoA = 0;}

		if(!empty($post['anno'])){
			$AND_year = 'AND (';
			$count = count($post['anno'])-1;
			foreach($post['anno'] as $k => $anno){
				if($k < $count){
					$AND_year .= 'GS_art_year = '.$anno.' OR ';
				}else if($k == $count){
					$AND_year .= 'GS_art_year = '.$anno.' ';
				}	
			}
			$AND_year .= ')';
		}else{
			$AND_year = '';
		}
		if( $annoDA && $annoA){
			if( $annoA < $annoDA){
				$AND_DA_A = "AND GS_art_year BETWEEN $annoA AND $annoDA ";
			}else{
				$AND_DA_A = "AND GS_art_year BETWEEN $annoDA AND $annoA ";
			}
		}else{
			$AND_DA_A = '';
		}

		$sql = "SELECT * 
				FROM google_scholar_art 
				WHERE 1 = 1
				$AND_aut
				$AND_tit
				$AND_year
				$AND_DA_A	
		";
		
		$result = mysqli_query($conn, $sql);
		$articles = mysqli_fetch_all($result, MYSQLI_ASSOC);
		//pretty_print($articles);
		return $articles;

	}
	function getYearGS() {
		global $conn;
		$sql = "SELECT DISTINCT GS_art_year FROM google_scholar_art  ";
		$result = mysqli_query($conn, $sql);
		$year = mysqli_fetch_all($result, MYSQLI_ASSOC);
		return $year;
	}
/* * * * * * * * * * * * * * *
*		LOGIN
* * * * * * * * * * * * * * */ 
// call the login() function if register_btn is clicked
if (isset($_POST['login_btn'])) {
	login();
}
//all
function getAll(){
	$proceedings_balisage = getBalisageProceedings();
	$BalisageTopics = getBalisageTopics();
	$arr1_raw = array_merge($BalisageTopics, $proceedings_balisage);
	foreach($arr1_raw as $k => $val){
		$arr1[$k]['title'] = $val['b_art_title'];
		$arr1[$k]['link'] = $val['b_art_link'];
		$arr1[$k]['author'] = $val['b_art_author'];
		$arr1[$k]['year'] = $val['b_art_year'];
		$arr1[$k]['table'] = 'Balisage';
	}

	$dsh_issues = getAllIssues();
	$dsh_advanced_art = get_advanced_articles();
	$arr2_row = array_merge($dsh_issues, $dsh_advanced_art);
	foreach($arr2_row as $k => $val){
		$arr2[$k]['title'] = $val['dsh_art_title'];
		$arr2[$k]['link'] = $val['dsh_art_link'];
		$arr2[$k]['author'] = $val['dsh_art_author'];
		$arr2[$k]['year'] = $val['dsh_art_year'];
		$arr2[$k]['table'] = 'DSH';
	}
	$final1_raw = array_merge($arr1, $arr2);
	$final1 = array_unique($final1_raw, SORT_REGULAR);

	$jtei_art = get_JTEI_Issues();
	foreach($jtei_art as $k => $val){
		$arr3[$k]['title'] = $val['jtei_art_title'];
		$arr3[$k]['link'] = $val['jtei_art_link'];
		$arr3[$k]['author'] = $val['jtei_art_author'];
		$arr3[$k]['year'] = $val['jtei_art_year'];
		$arr3[$k]['table'] = 'JTEI';
	}
	$gs_art = getAllGoogleArticles();
	foreach($gs_art as $k => $val){
		$arr4[$k]['title'] = $val['GS_art_title'];
		$arr4[$k]['link'] = $val['GS_art_link'];
		$arr4[$k]['author'] = $val['GS_art_author'];
		$arr4[$k]['year'] = $val['GS_art_year'];
		$arr4[$k]['table'] = 'Google scholar';
	}
	$final2_raw = array_merge($arr3, $arr4);
	$final2 = array_unique($final2_raw, SORT_REGULAR);

	$final_raw = array_merge($final1, $final2);
	$final = array_unique($final_raw, SORT_REGULAR);
	return $final;
}
function getTable(){
	global $conn;
		$sql = "SELECT tab_title FROM table_sites  ";
		$result = mysqli_query($conn, $sql);
		$tab_raw = mysqli_fetch_all($result, MYSQLI_ASSOC);
		$tab = str_replace("_art","",$tab_raw);

		foreach($tab as $k =>$val){
			$title = str_replace("_art","",$val['tab_title']);
			$arr[$k+1] = str_replace("_"," ",$title);
		}
		return $arr;
}
function filtroAvanzatoAll($post){
	global $conn;
	$arr1 = []; $arr2 = []; $arr3 = []; $arr4 = []; 
	foreach($post['sito'] as $k => $val){
		if($val == 1){ //SEARCH BALISAGE
			if(!empty($post['autore'])){
				$autore_bali = $post['autore'];
				$AND_aut = "AND b_art_author LIKE '%$autore_bali%' ";
			}else{
				$AND_aut = '';
				}
			if(!empty($post['titolo'])){
				$titolo_bali = $post['titolo'];
				$AND_tit = "AND b_art_title LIKE '%$titolo_bali%' ";
			}else{
				$AND_tit = '';
			}

			if(!empty($post['annoDA'])){$annoDA_bali = $post['annoDA'];}else{$annoDA_bali = 0;}
			if(!empty($post['annoA'])) {$annoA_bali = $post['annoA'];}else{$annoA_bali = 0;}

			if(!empty($post['anno'])){
				$AND_year = 'AND (';
				$count = count($post['anno'])-1;
				foreach($post['anno'] as $k => $anno_bali){
					if($k < $count){
						$AND_year .= 'b_art_year = '.$anno_bali.' OR ';
					}else if($k == $count){
						$AND_year .= 'b_art_year = '.$anno_bali.' ';
					}	
				}
				$AND_year .= ')';
			}else{
				$AND_year = '';
			}

			if( $annoDA_bali && $annoA_bali){
				//$AND_DA_A = "AND b_art_year BETWEEN $annoDA AND $annoA ";	
				if( $annoA_bali < $annoDA_bali){
					$AND_DA_A = "AND b_art_year BETWEEN $annoA_bali AND $annoDA_bali ";
				}else{
					$AND_DA_A = "AND b_art_year BETWEEN $annoDA_bali AND $annoA_bali ";
				}
			}else{
				$AND_DA_A = '';
			}

			$sql = "SELECT * 
			FROM balisage_art 
			WHERE 1 = 1
			$AND_aut
			$AND_tit
			$AND_year
			$AND_DA_A	
			";
			$result = mysqli_query($conn, $sql);
			$articles_bali = mysqli_fetch_all($result, MYSQLI_ASSOC);
			
		}
		
		if($val == 2){ //SEARCH DSH 
			if(!empty($post['autore'])){
				$autore_dsh = $post['autore'];
				$AND_aut = "AND dsh_art_author LIKE '%$autore_dsh%' ";
			}else{
				$AND_aut = '';
				}
			if(!empty($post['titolo'])){
				$titolo_dsh = $post['titolo'];
				$AND_tit = "AND dsh_art_title LIKE '%$titolo_dsh%' ";
			}else{
				$AND_tit = '';
			}
	
			if(!empty($post['annoDA'])){$annoDA_dsh = $post['annoDA'];}else{$annoDA_dsh = 0;}
			if(!empty($post['annoA'])) {$annoA_dsh = $post['annoA'];}else{$annoA_dsh = 0;}
	
			if(!empty($post['anno'])){
				$AND_year = 'AND (';
				$count = count($post['anno'])-1;
				foreach($post['anno'] as $k => $anno_dsh){
					if($k < $count){
						$AND_year .= 'dsh_art_year = '.$anno_dsh.' OR ';
					}else if($k == $count){
						$AND_year .= 'dsh_art_year = '.$anno_dsh.' ';
					}	
				}
				$AND_year .= ')';
			}else{
				$AND_year = '';
			}

			if( $annoDA_dsh && $annoA_dsh){
				//$AND_DA_A = "AND b_art_year BETWEEN $annoDA AND $annoA ";	
				if( $annoA_dsh < $annoDA_dsh){
					$AND_DA_A = "AND dsh_art_year BETWEEN $annoA_dsh AND $annoDA_dsh ";
				}else{
					$AND_DA_A = "AND dsh_art_year BETWEEN $annoDA_dsh AND $annoA_dsh ";
				}
					
				
			}else{
				$AND_DA_A = '';
			}
	
			$sql_dsh = "SELECT * 
					FROM DSH_art 
					WHERE 1 = 1
					$AND_aut
					$AND_tit
					$AND_year
					$AND_DA_A	
			";
			$result_dsh = mysqli_query($conn, $sql_dsh);
			$articles_dsh = mysqli_fetch_all($result_dsh, MYSQLI_ASSOC);
			
		}
		
		if($val == 3){
			if(!empty($post['autore'])){
				$autore_jtei = $post['autore'];
				$AND_aut = "AND jtei_art_author LIKE '%$autore_jtei%' ";
			}else{
				$AND_aut = '';
				}
			if(!empty($post['titolo'])){
				$titolo_jtei = $post['titolo'];
				$AND_tit = "AND jtei_art_title LIKE '%$titolo_jtei%' ";
			}else{
				$AND_tit = '';
			}
	
			if(!empty($post['annoDA'])){$annoDA_jtei = $post['annoDA'];}else{$annoDA_jtei = 0;}
			if(!empty($post['annoA'])) {$annoA_jtei = $post['annoA'];}else{$annoA_jtei = 0;}
	
			if(!empty($post['anno'])){
				$AND_year = 'AND (';
				$count = count($post['anno'])-1;
				foreach($post['anno'] as $k => $anno_jtei){
					if($k < $count){
						$AND_year .= 'jtei_art_year = '.$anno_jtei.' OR ';
					}else if($k == $count){
						$AND_year .= 'jtei_art_year = '.$anno_jtei.' ';
					}	
				}
				$AND_year .= ')';
			}else{
				$AND_year = '';
			}
			if( $annoDA_jtei && $annoA_jtei){
				//$AND_DA_A = "AND b_art_year BETWEEN $annoDA AND $annoA ";	
				if( $annoA_jtei < $annoDA_jtei){
					$AND_DA_A = "AND jtei_art_year BETWEEN $annoA_jtei AND $annoDA_jtei ";
				}else{
					$AND_DA_A = "AND jtei_art_year BETWEEN $annoDA_jtei AND $annoA_jtei ";
				}
			}else{
				$AND_DA_A = '';
			}
	
			$sql_jtei = "SELECT * 
					FROM jtei_art 
					WHERE 1 = 1
					$AND_aut
					$AND_tit
					$AND_year
					$AND_DA_A	
			";
			
			$result_jtei = mysqli_query($conn, $sql_jtei);
			$articles_jtei = mysqli_fetch_all($result_jtei, MYSQLI_ASSOC);
			
		}
		
		if($val == 4){
			if(!empty($post['autore'])){
				$autore_GS = $post['autore'];
				$AND_aut = "AND GS_art_author LIKE '%$autore_GS%' ";
			}else{
				$AND_aut = '';
				}
			if(!empty($post['titolo'])){
				$titolo_GS = $post['titolo'];
				$AND_tit = "AND GS_art_title LIKE '%$titolo_GS%' ";
			}else{
				$AND_tit = '';
			}
	
			if(!empty($post['annoDA'])){$annoDA_GS = $post['annoDA'];}else{$annoDA_GS = 0;}
			if(!empty($post['annoA'])) {$annoA_GS = $post['annoA'];}else{$annoA_GS = 0;}
	
			if(!empty($post['anno'])){
				$AND_year = 'AND (';
				$count = count($post['anno'])-1;
				foreach($post['anno'] as $k => $anno_GS){
					if($k < $count){
						$AND_year .= 'GS_art_year = '.$anno_GS.' OR ';
					}else if($k == $count){
						$AND_year .= 'GS_art_year = '.$anno_GS.' ';
					}	
				}
				$AND_year .= ')';
			}else{
				$AND_year = '';
			}
			if( $annoDA_GS && $annoA_GS){
				if( $annoA_GS < $annoDA_GS){
					$AND_DA_A = "AND GS_art_year BETWEEN $annoA_GS AND $annoDA_GS ";
				}else{
					$AND_DA_A = "AND GS_art_year BETWEEN $annoDA_GS AND $annoA_GS ";
				}
			}else{
				$AND_DA_A = '';
			}
	
			$sql_GS = "SELECT * 
					FROM google_scholar_art 
					WHERE 1 = 1
					$AND_aut
					$AND_tit
					$AND_year
					$AND_DA_A	
			";
			
			$result_GS = mysqli_query($conn, $sql_GS);
			$articles_GS = mysqli_fetch_all($result_GS, MYSQLI_ASSOC);
			
		}	
	}
	if(!empty($articles_bali)){
		foreach($articles_bali as $k => $val){
			$arr1[$k]['title'] = $val['b_art_title'];
			$arr1[$k]['link'] = $val['b_art_link'];
			$arr1[$k]['author'] = $val['b_art_author'];
			$arr1[$k]['year'] = $val['b_art_year'];
			$arr1[$k]['table'] = 'Balisage';
		}
	}
	if(!empty($articles_dsh)){
		foreach($articles_dsh as $k => $val){
			$arr2[$k]['title'] = $val['dsh_art_title'];
			$arr2[$k]['link'] = $val['dsh_art_link'];
			$arr2[$k]['author'] = $val['dsh_art_author'];
			$arr2[$k]['year'] = $val['dsh_art_year'];
			$arr2[$k]['table'] = 'DSH';
		}
	}
	if(!empty($articles_jtei)){
		foreach($articles_jtei as $k => $val){
			$arr3[$k]['title'] = $val['jtei_art_title'];
			$arr3[$k]['link'] = $val['jtei_art_link'];
			$arr3[$k]['author'] = $val['jtei_art_author'];
			$arr3[$k]['year'] = $val['jtei_art_year'];
			$arr3[$k]['table'] = 'JTEI';
		}
	}
	if(!empty($articles_GS)){
		foreach($articles_GS as $k => $val){
			$arr4[$k]['title'] = $val['GS_art_title'];
			$arr4[$k]['link'] = $val['GS_art_link'];
			$arr4[$k]['author'] = $val['GS_art_author'];
			$arr4[$k]['year'] = $val['GS_art_year'];
			$arr4[$k]['table'] = 'Google scholar';
		}
	}
	$final1 = array_merge($arr1,$arr2);
	$final2 = array_merge($arr3,$arr4);
	$final = array_merge($final1,$final2);
	return $final;

}
// LOGIN USER
function login(){
	global $conn, $username, $errors;

	// grap form values
	$username = $_POST['username'];
	$password = $_POST['password'];

		$query = "SELECT * FROM users WHERE username='$username' AND password='$password' LIMIT 1";
		$results = mysqli_query($conn, $query);

		if (mysqli_num_rows($results) == 1) { // user found
			// check if user is admin or user
			$logged_in_user = mysqli_fetch_assoc($results);	
			$_SESSION['user'] = $logged_in_user;
			header('location: admin/admin.php');		  
			
		}
}





/* * * * * * * * * * * * * * *
*		ACM
* * * * * * * * * * * * * * */
	require_once( ROOT_PATH . '/includes/ACMscrape.php');
	function getAllArticles(){
		// use global $conn object in function
		global $conn;
		$sql = "SELECT * FROM acm_articles";;
		$result = mysqli_query($conn, $sql);

		// fetch all posts as an associative array called $posts
		$articles = mysqli_fetch_all($result, MYSQLI_ASSOC);

		return $articles;
	}
	function add_ACM_article_from_onePage($url){
		global $conn;
			
			$articles = scrapeACM($url);
			pretty_print($articles);
			$arr = [];
			$i=0;
			foreach($articles as $k =>$val){
					$arr[$i]['title'] 		= $val['title'];
					$arr[$i]['link'] 		= $val['link'];
					$arr[$i]['year'] 		= $val['year'];
					$arr[$i]['numCites'] 	= $val['numCites'];
					$arr[$i]['numDownload']	= $val['numDownload'];
					$i++;
			}	
				$counter = 0;
				$len= count($arr);

				foreach($arr as $key =>$val){
					$title 		= $val['title'];
					$link		= $val['link'];
					$year		= $val['year'];
					$numCities	= $val['numCites'];
					$numDownload= $val['numDownload'];
					
					if( $counter < $len){
						$query = "INSERT INTO acm_articles (acm_art_id, acm_art_title, acm_art_link, acm_art_year, acm_art_numCite,acm_art_numDownload) 
						VALUES('', '$title', '$link','$year','$numCities','$numDownload')";
						mysqli_query($conn, $query);
						$counter++;
					}else{
						break;
					}
			
				}  					
	}
	function delete_all(){
		// use global $conn object in function
		global $conn;
		$sql = "SELECT acm_art_id FROM `acm_articles` WHERE 1=1";
		$result = mysqli_query($conn, $sql);

		// fetch all posts as an associative array called $posts
		$count = mysqli_fetch_all($result, MYSQLI_ASSOC);
		if(!empty($count)){
			foreach($count as $k => $val){
				$arr[] = $val['acm_art_id'];
			}

			foreach($arr as $key => $id){
				$delete_sql = "DELETE FROM acm_articles where acm_art_id = $id";
				$result = mysqli_query($conn, $delete_sql);
			}
		}
	}
?>