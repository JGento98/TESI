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
		
		function getBalisageProceedings() {
			global $conn;
			
			$sql = "SELECT * FROM articles WHERE art_type='P' AND  art_table_id = 1 ";
			$result = mysqli_query($conn, $sql);

			$articles = mysqli_fetch_all($result, MYSQLI_ASSOC);

			return $articles;
		}
		function getTopic() {
			global $conn;
			$sql = "SELECT DISTINCT art_topic FROM articles WHERE art_type='T' AND art_table_id = 1 ";
			$result = mysqli_query($conn, $sql);
			$topic = mysqli_fetch_all($result, MYSQLI_ASSOC);
			return $topic;
		}
		function getVolume() {
			global $conn;
			$sql = "SELECT DISTINCT art_volume FROM articles WHERE art_type='P' AND art_table_id = 1 ";
			$result = mysqli_query($conn, $sql);
			$vol = mysqli_fetch_all($result, MYSQLI_ASSOC);
			return $vol;
		}
		function getYear() {
			global $conn;
			$sql = "SELECT DISTINCT art_year FROM articles WHERE  art_table_id = 1   ";
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
				$AND_aut = "AND art_author LIKE '%$autore%' ";
			}else{
				$AND_aut = '';
				}
			if(!empty($post['titolo'])){
				$titolo = $post['titolo'];
				$AND_tit = "AND art_title LIKE '%$titolo%' ";
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
						$AND_year .= 'art_year = '.$anno.' OR ';
					}else if($k == $count){
						$AND_year .= 'art_year = '.$anno.' ';
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
						$AND_volume .= 'art_volume = "'.$volume.'" OR ';
					}else if($k == $count){
						$AND_volume .= 'art_volume = "'.$volume.'" ';
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
						$AND_topic .= 'art_topic = "'.$topic.'" OR ';
					}else if($k == $count){
						$AND_topic .= 'art_topic = "'.$topic.'" ';
					}	
				}
				$AND_topic .= ')';
			}else{
				$AND_topic = '';
			}
			if( $annoDA && $annoA){
				//$AND_DA_A = "AND art_year BETWEEN $annoDA AND $annoA ";	
				if( $annoA < $annoDA){
					$AND_DA_A = "AND art_year BETWEEN $annoA AND $annoDA ";
				}else{
					$AND_DA_A = "AND art_year BETWEEN $annoDA AND $annoA ";
				}
					
				
			}else{
				$AND_DA_A = '';
			}

			$sql = "SELECT * 
			FROM articles 
			WHERE 1 = 1
			AND art_type = $tipo AND art_table_id = 1
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
			$delete_sql = "DELETE FROM articles where art_type = 'P' AND art_table_id = 1";
			$result = mysqli_query($conn, $delete_sql);
		}
		//recupera tutti gli articoli flaggati T (topics)
		function getBalisageTopics() {	
			global $conn;
			
			$sql = "SELECT * FROM articles WHERE art_type='T' AND  art_table_id = 1 ";
			$result = mysqli_query($conn, $sql);

			$articles = mysqli_fetch_all($result, MYSQLI_ASSOC);

			return $articles;
		}
		//elimina i topic
		function deleteAlltopics(){
			global $conn;
			$delete_sql = "DELETE FROM articles where art_type = 'T' AND art_table_id = 1";
			$result = mysqli_query($conn, $delete_sql);
		}
		function searchTopic($topic_to_search){
			global $conn;
				$sql = "SELECT * FROM articles WHERE art_type='T' AND  art_table_id = 1 AND art_topic = '$topic_to_search' ";
				$result = mysqli_query($conn, $sql);
				$articles = mysqli_fetch_all($result, MYSQLI_ASSOC);

			return $articles;

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
		
			foreach($arr as $key =>$val){
				$title 	= trim($val['title']);
				$link	= trim($val['link']);
				$vol	= trim($val['volume']);
				$year	= trim($val['year']);
				$author	= trim($val['author']);

				$sql_check = "SELECT `art_title` FROM articles WHERE `art_title`= '$title' AND  art_table_id = 1 ";
				$result = mysqli_query($conn, $sql_check);
				$art = mysqli_fetch_all($result, MYSQLI_ASSOC);
	
				if(empty($art)){
				$query = "INSERT INTO articles
				(art_id, art_title, art_author, art_link, art_year, art_topic, art_volume, art_type, art_table_id ) 
				VALUES('','$title', '$author' ,'$link','$year',null,'$vol','P',1 )";
				mysqli_query($conn, $query);
				}				
			}
			$day = date("Y-m-d");
			$query_logs =   "INSERT INTO logs(`id`, `last_update`, `table_id`, `art_type`) 
			VALUES('','$day', 1,'P')";
			mysqli_query($conn, $query_logs);	
		}
	  
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
				
				$sql_check = "SELECT `art_title` FROM articles WHERE `art_title`= '$title' AND  art_table_id = 1 ";
				$result = mysqli_query($conn, $sql_check);
				$art = mysqli_fetch_all($result, MYSQLI_ASSOC);

				if(empty($art)){
					$query = "INSERT INTO articles
					(art_id, art_title, art_author, art_link, art_year, art_topic, art_volume, art_type, art_table_id ) 
					VALUES('','$title', '$author' ,'$link','$year','$topic','$volume','T',1 )";
					mysqli_query($conn, $query);
				}
				
			}  		
			$day = date("Y-m-d");
			$query_logs =   "INSERT INTO logs
			(`id`, `last_update`, `table_id`, `art_type`) VALUES('','$day', 1,'T')";
			mysqli_query($conn, $query_logs);				
		}

/* * * * * * * * * * * * * * *
*		DSH
* * * * * * * * * * * * * * */
	require_once( ROOT_PATH . '/includes/DSHScrape.php');
	function getAllIssues(){
		global $conn;
		$sql = "SELECT * FROM articles WHERE art_type ='IS' AND art_table_id = 2 ";;
		$result = mysqli_query($conn, $sql);

		$articles = mysqli_fetch_all($result, MYSQLI_ASSOC);

		return $articles;
	}
	function get_advanced_articles(){
		global $conn;
		$sql = "SELECT * FROM articles WHERE art_type ='AD' AND art_table_id = 2 ";;
		$result = mysqli_query($conn, $sql);
		$articles = mysqli_fetch_all($result, MYSQLI_ASSOC);
		return $articles;
	}
	function getVolumeDSH() {
		global $conn;
		$sql = "SELECT DISTINCT art_volume FROM articles WHERE art_type='IS' AND art_table_id = 2 ";
		$result = mysqli_query($conn, $sql);
		$vol = mysqli_fetch_all($result, MYSQLI_ASSOC);
		return $vol;
	}
	function getYearDSH() {
		global $conn;
		$sql = "SELECT DISTINCT art_year FROM articles  ";
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
		foreach($arr as $key =>$val){
			$title 	= $val['title'];
			$link	= $val['link'];
			$vol	= $val['volume'];
			$year	= $val['year'];
			$author = $val['authors'];

			$sql_check = "SELECT `art_title` FROM articles WHERE `art_title`= '$title' AND  art_table_id = 2 ";
			$result = mysqli_query($conn, $sql_check);
			$art = mysqli_fetch_all($result, MYSQLI_ASSOC);

			if(empty($art)){
				$query = "INSERT INTO articles
				(art_id, art_title, art_author, art_link, art_year, art_topic, art_volume, art_type, art_table_id ) 
				VALUES('','$title', '$author' ,'$link','$year',null,'$vol','IS',2 )";
				mysqli_query($conn, $query);
			}
		}  		
		$day = date("Y-m-d");
		$query_logs =   "INSERT INTO logs
		(`id`, `last_update`, `table_id`, `art_type`) VALUES('','$day', 2,'IS')";
		mysqli_query($conn, $query_logs);				
	}
	function add_advance_articles(){
		global $conn;
		$dshURL   = 'https://academic.oup.com/dsh/advance-articles';
		$advance_articles = scrapeDSH_advance_articles($dshURL);
		
			foreach($advance_articles as $key =>$val){
				$title 	= $val['title'];
				$link	= $val['link'];
				$year	= $val['year'];
				$author = $val['authors'];

				$sql_check = "SELECT `art_title` FROM articles WHERE `art_title`= '$title' AND  art_table_id = 2 ";
				$result = mysqli_query($conn, $sql_check);
				$art = mysqli_fetch_all($result, MYSQLI_ASSOC);

				if(empty($art)){
					$query = "INSERT INTO articles
					(art_id, art_title, art_author, art_link, art_year, art_topic, art_volume, art_type, art_table_id ) 
					VALUES('','$title', '$author' ,'$link','$year',null,null,'AD',2 )";
					mysqli_query($conn, $query);
				}
			}  
			$day = date("Y-m-d");
			$query_logs =   "INSERT INTO logs
			(`id`, `last_update`, `table_id`, `art_type`) VALUES('','$day', 2,'AD')";
			mysqli_query($conn, $query_logs);							
	}
	function delete_advance_articles(){
		global $conn;
		$delete_sql = "DELETE FROM articles where art_type ='AD' AND art_table_id = 2";
		$result = mysqli_query($conn, $delete_sql);
		
		
	}
	function delete_all_Issues(){
		global $conn;
		$delete_sql = "DELETE FROM articles where  art_type ='IS' AND art_table_id = 2";
		$result = mysqli_query($conn, $delete_sql);
	}
	function filtroAvanzatoDSH($post){
		global $conn;
		$tipo = '"'.$_GET['tiporicerca'].'"';
		if(!empty($post['autore'])){
			$autore = $post['autore'];
			$AND_aut = "AND art_author LIKE '%$autore%' ";
		}else{
			$AND_aut = '';
			}
		if(!empty($post['titolo'])){
			$titolo = $post['titolo'];
			$AND_tit = "AND art_title LIKE '%$titolo%' ";
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
					$AND_year .= 'art_year = '.$anno.' OR ';
				}else if($k == $count){
					$AND_year .= 'art_year = '.$anno.' ';
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
					$AND_volume .= 'art_volume = "'.$volume.'" OR ';
				}else if($k == $count){
					$AND_volume .= 'art_volume = "'.$volume.'" ';
				}	
			}
			$AND_volume .= ')';
		}else{
			$AND_volume = '';
		}
		if( $annoDA && $annoA){
			//$AND_DA_A = "AND art_year BETWEEN $annoDA AND $annoA ";	
			if( $annoA < $annoDA){
				$AND_DA_A = "AND art_year BETWEEN $annoA AND $annoDA ";
			}else{
				$AND_DA_A = "AND art_year BETWEEN $annoDA AND $annoA ";
			}
				
			
		}else{
			$AND_DA_A = '';
		}

		$sql = "SELECT * 
				FROM articles 
				WHERE 1 = 1
				AND art_type = $tipo AND art_table_id = 2 
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
		$sql = "SELECT * FROM articles WHERE  art_table_id = 3 ";;
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
				foreach($arr as $key =>$val){
					$title 	= $val['title'];
					$link	= $val['link'];
					$year	= $val['year'];
					$author = $val['author'];

					$sql_check = "SELECT `art_title` FROM articles WHERE `art_title`= '$title' AND  art_table_id = 3 ";
					$result = mysqli_query($conn, $sql_check);
					$art = mysqli_fetch_all($result, MYSQLI_ASSOC);
		
					if(empty($art)){
						$query = "INSERT INTO articles
						(art_id, art_title, art_author, art_link, art_year, art_topic, art_volume, art_type, art_table_id ) 
						VALUES('','$title', '$author' ,'$link','$year',null,null,null,3 )";
							
						mysqli_query($conn, $query);
					}
					
			
				} 
			$day = date("Y-m-d");
			$query_logs =   "INSERT INTO logs
			(`id`, `last_update`, `table_id`, `art_type`) VALUES('','$day', 3, null)";
			mysqli_query($conn, $query_logs); 					
	}
	function delete_JTEI_Issues(){
		global $conn;
			$delete_sql = "DELETE FROM articles WHERE art_table_id = 3";
			$result = mysqli_query($conn, $delete_sql);
	}
	function filtroAvanzatoJTEI($post){
		global $conn;
		$tipo = '"'.$_GET['tiporicerca'].'"';
		if(!empty($post['autore'])){
			$autore = $post['autore'];
			$AND_aut = "AND art_author LIKE '%$autore%' ";
		}else{
			$AND_aut = '';
			}
		if(!empty($post['titolo'])){
			$titolo = $post['titolo'];
			$AND_tit = "AND art_title LIKE '%$titolo%' ";
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
					$AND_year .= 'art_year = '.$anno.' OR ';
				}else if($k == $count){
					$AND_year .= 'art_year = '.$anno.' ';
				}	
			}
			$AND_year .= ')';
		}else{
			$AND_year = '';
		}
		if( $annoDA && $annoA){
			//$AND_DA_A = "AND art_year BETWEEN $annoDA AND $annoA ";	
			if( $annoA < $annoDA){
				$AND_DA_A = "AND art_year BETWEEN $annoA AND $annoDA ";
			}else{
				$AND_DA_A = "AND art_year BETWEEN $annoDA AND $annoA ";
			}
		}else{
			$AND_DA_A = '';
		}

		$sql = "SELECT * 
				FROM articles  
				WHERE art_table_id = 3 
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
		$sql = "SELECT DISTINCT art_year FROM articles WHERE art_table_id = 3   ";
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
			foreach($arr as $key =>$val){
				$title 		= $val['title'];
				$link		= $val['link'];
				$year		= $val['year'];
				$author     = $val['authors'];
				//$numCities	= $val['numCites'];
				
			$sql_check = "SELECT `art_title` FROM articles WHERE `art_title`= '$title' AND  art_table_id = 4 ";
			$result = mysqli_query($conn, $sql_check);
			$art = mysqli_fetch_all($result, MYSQLI_ASSOC);

				if(empty($art)){
						$query = "INSERT INTO articles
						(art_id, art_title, art_author, art_link, art_year, art_topic, art_volume, art_type, art_table_id ) 
						VALUES('','$title', '$author' ,'$link','$year',null,null,null,4 )";
						mysqli_query($conn, $query);
					
				}
			}
			$day = date("Y-m-d");
			$query_logs =   "INSERT INTO logs
			(`id`, `last_update`, `table_id`, `art_type`) VALUES('','$day', 4, null)";
			mysqli_query($conn, $query_logs); 	  					
			
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
			
			foreach($arr as $key =>$val){
				$title 		= $val['title'];
				$link		= $val['link'];
				$year		= $val['year'];
				$author     = $val['authors'];
				//$numCities	= $val['numCites'];

			$sql_check = "SELECT `art_title` FROM articles WHERE `art_title`= '$title' AND  art_table_id = 4 ";
			$result = mysqli_query($conn, $sql_check);
			$art = mysqli_fetch_all($result, MYSQLI_ASSOC);

				if(empty($art)){
					$query = "INSERT INTO articles
					(art_id, art_title, art_author, art_link, art_year, art_topic, art_volume, art_type, art_table_id ) 
					VALUES('','$title', '$author' ,'$link','$year',null,null,null, 4 )";
					mysqli_query($conn, $query);
						
				}
			}  					
			
	}

	function getAllGoogleArticles(){
		global $conn;
		$sql = "SELECT * FROM articles WHERE art_table_id = 4";
		$result = mysqli_query($conn, $sql);
		$articles = mysqli_fetch_all($result, MYSQLI_ASSOC);

		return $articles;
	}
	function delete_all_GS(){
		global $conn;
		$delete_sql = "DELETE FROM articles where art_table_id = 4";
		$result = mysqli_query($conn, $delete_sql);
		
	}
	function filtroAvanzatoGS($post){
		global $conn;
		if(!empty($post['autore'])){
			$autore = $post['autore'];
			$AND_aut = "AND art_author LIKE '%$autore%' ";
		}else{
			$AND_aut = '';
			}
		if(!empty($post['titolo'])){
			$titolo = $post['titolo'];
			$AND_tit = "AND art_title LIKE '%$titolo%' ";
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
					$AND_year .= 'art_year = '.$anno.' OR ';
				}else if($k == $count){
					$AND_year .= 'art_year = '.$anno.' ';
				}	
			}
			$AND_year .= ')';
		}else{
			$AND_year = '';
		}
		if( $annoDA && $annoA){
			if( $annoA < $annoDA){
				$AND_DA_A = "AND art_year BETWEEN $annoA AND $annoDA ";
			}else{
				$AND_DA_A = "AND art_year BETWEEN $annoDA AND $annoA ";
			}
		}else{
			$AND_DA_A = '';
		}

		$sql = "SELECT * 
				FROM articles 
				WHERE art_table_id = 4 
				$AND_aut
				$AND_tit
				$AND_year
				$AND_DA_A	
		";
		
		$result = mysqli_query($conn, $sql);
		$articles = mysqli_fetch_all($result, MYSQLI_ASSOC);
		return $articles;

	}
	function getYearGS() {
		global $conn;
		$sql = "SELECT DISTINCT art_year FROM articles WHERE art_table_id = 4   ";
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
	global $conn;
		$sql = "SELECT DISTINCT * FROM articles ";
		$result = mysqli_query($conn, $sql);
		$arr = mysqli_fetch_all($result, MYSQLI_ASSOC);
		return $arr;
}
function getTable(){
	global $conn;
		$sql = "SELECT * FROM table_sites ";
		$result = mysqli_query($conn, $sql);
		$arr = mysqli_fetch_all($result, MYSQLI_ASSOC);
		return $arr;
}
function filtroAvanzatoAll($post){
	global $conn;
	if(!empty($post['sito'])){
		$countSites = count($post['sito'])-1;
		$AND_sites = 'AND (';
		foreach($post['sito'] as $k => $sito_id){
			if($k < $countSites){
				$AND_sites .= 'art_table_id = '.$sito_id.' OR ';
			}else if($k == $countSites){
				$AND_sites .= 'art_table_id = '.$sito_id.' ';;
			}	
		}
		$AND_sites .= ') ';

	}
		if(!empty($post['autore'])){
			$autore = $post['autore'];
			$AND_aut = "AND art_author LIKE '%$autore%' ";
		}else{
			$AND_aut = '';
			}
		if(!empty($post['titolo'])){
			$titolo = $post['titolo'];
			$AND_tit = "AND art_title LIKE '%$titolo%' ";
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
					$AND_year .= 'art_year = '.$anno.' OR ';
				}else if($k == $count){
					$AND_year .= 'art_year = '.$anno.' ';
				}	
			}
			$AND_year .= ')';
		}else{
			$AND_year = '';
		}
		if( $annoDA && $annoA){
			//$AND_DA_A = "AND art_year BETWEEN $annoDA AND $annoA ";	
			if( $annoA < $annoDA){
				$AND_DA_A = "AND art_year BETWEEN $annoA AND $annoDA ";
			}else{
				$AND_DA_A = "AND art_year BETWEEN $annoDA AND $annoA ";
			}
		}else{
			$AND_DA_A = '';
		}

		$sql = "SELECT * 
				FROM articles  
				WHERE 1 = 1
				$AND_sites
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
function getLogs($tab_id, $art_type){
	global $conn;
		if(empty($art_type)){
			$sql = "SELECT max(`last_update`) as ultima_mod FROM logs WHERE `table_id`= $tab_id ";
			$result = mysqli_query($conn, $sql);
			$arr = mysqli_fetch_all($result, MYSQLI_ASSOC);
		}else{
			$sql = "SELECT max(`last_update`) as ultima_mod FROM logs  WHERE `art_type` = '$art_type' AND `table_id`= $tab_id ";
			$result = mysqli_query($conn, $sql);
			$arr = mysqli_fetch_all($result, MYSQLI_ASSOC);
		}
		return $arr;
}

function getTopicLABEL($topic){
	global $conn;
		$sql = "SELECT DISTINCT * FROM articles WHERE art_table_id = 1 AND art_topic = '$topic' AND art_type = 'T' ";
		$result = mysqli_query($conn, $sql);
		$arr = mysqli_fetch_all($result, MYSQLI_ASSOC);
		return $arr;
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