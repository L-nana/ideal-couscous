<html>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>プログラミングブログ</title>
	</head>
		<body>
		<h1>プログラミングブログ</h1>
		<form method="POST" action="/mission_2-6.php">
		<p>名前:<input type ="text" name="name"/></p>
		<p>コメント:<input type = "text" name="kome"/></p>
		パスワード設定:<input type ="text" name = "pass"/>
		<p><input type="submit"name="btn"value="送信"/></p>
		</form>
		<p>・パスワードを入力しなければコメントは打てません。</p>
		
		<!2-2追記>
		<?php
			$name = $_POST['name'];//mission_2-5.phpからnameを受ケトル
			$kome = $_POST['kome'];//mission_2-5.phpからkomeを受ケトル
			$pass = $_POST['pass'];
			if(ctype_digit($_POST['bangou'])){  //数字かどうかで分けるctype_digit
			$bangou = $_POST['bangou'];     //mission_2-5.phpからbangouを受け取る
			//echo $bangou.'番を削除します<br>';
			}else{
			$bangou = "";
			}
			if(ctype_digit($_POST['hensyuu'])){  //数字かどうかで分けるctype_digit
			$hensyuu = $_POST['hensyuu'];     //mission_2-5.phpからhensyuuを受け取る
			//echo $hensyuu.'番を編集します<br/>';
			}
			$filename='kadai2-2.txt';//$filenameを指定
			/*--------------ここまで変数定義-------------*/
			
			
			$prof = $name && $kome;//nameとkomeの両方が入力されないと書きださない
			$prof = $prof && $pass;
			if(!empty($prof)){
			/*--------------カウント処理はじめ-----------*/
				$fp2 = fopen( 'count_2-2.txt','r+'); // ファイル開く
				$count = fgets( $fp2, 10 ); // 9桁分値読み取り
				$count = $count + 1; // 値+1（カウントアップ）
				rewind( $fp2 ); // ファイルポインタを先頭に戻す
				fputs( $fp2, $count ); // 値書き込み
				fclose( $fp2 ); // ファイル閉じる
				//echo '<p>あなたのコメントは'.$count.'番に収納されました<br></p>';
			/*--------------カウント処理おわり-----------*/
				$fp = fopen($filename,'a');
				fwrite($fp,$count.'<>'.$name.'<>'.$kome.'<>'.date('Y/m/d H:i:s').'<>'.$pass.'<>'."\n");
				fclose($fp);
			}else{
			echo '未入力';
			}
		?>
		
		
		
		<!--2-4削除機能-->
		<hr>
		<form method="POST" action="/mission_2-6.php">
		削除対象番号:<input type = "text" name = "bangou"/>
		パスワード認証:<input type = "text" name = "pass2"/>
		<input type="submit"name="deletebtn"value="削除"/>
		</form>
		<?php
			if(ctype_digit($_POST['bangou'])){
			$hairetu = file($filename);//$hairetu に　$filenameを代入
			/*--------------2-6パスワード認証ここから-----------*/
				foreach($hairetu as $line){
					$data = explode("<>",$line);
					if($data[0] == $bangou){//番号
						$sakupass = $data[4];
						
					}
				}
				if($sakupass == $_POST['pass2']){
			/*--------------2-6パスワード認証ここまで-----------*/
					$i=0;
					$fp=fopen($filename,'w+');//fopen
					while($i < count($hairetu)){
						$ban = explode("<>",$hairetu[$i]);
						if($ban[0] != $bangou){//番号
							fwrite($fp,$hairetu[$i]);
						}
						$i++;
					}
					fclose($fp);
					echo $bangou.'番を削除しました';
				}else{
				echo 'パスワードが違います';
				}
			}
		?>
		<hr>
		
		
		<hr>
		<!--2-5追記-->
		<form method="POST" action="/mission_2-6.php">
		編集対象番号:<input name="hensyuu" type = "text" />
		パスワード認証:<input type = "text" name = "pass3"/>
		<button type="submit">編集</button>
		</form>
		
		<?php
			if(ctype_digit($_POST['hensyuu'])){	
				$filedata = file($filename);//$hairetu に　$filenameを代入
				/*--------------2-6パスワード認証ここから-----------*/
				foreach($filedata as $line){
					$data = explode("<>",$line);
					if($data[0] == $hensyuu){//番号
						$henpass = $data[4];
					}
				}
				if($henpass == $_POST['pass3']){
				/*--------------2-6パスワード認証ここまで-----------*/
					foreach($filedata as $line){
						$data = explode("<>",$line);
						if($data[0] == $hensyuu){//番号
							$edit_num = $data[0];                                                                                                                                                                         
							$user = $data[1];
							$text = $data[2];
							$time = $data[3];
							$pass = $data[4];
						}
					}
				}else{
				echo '・パスワードが違います';
				}
			}
		?>
		<form method="POST" action="/mission_2-6.php">
		<!--type = "hidden"はブラウザで見えないだけで他はtype = "text"と同じ-->
		<input name="edit_num" type = "hidden" value="<?echo $edit_num; ?>" />
		<p>名前:<input name="user" type = "text" value="<?echo $user; ?>" /></p>
		<p>コメント:<input name="text" type = "text" value="<?echo $text; ?>" /></p>
		<input name="time" type = "hidden" value="<?echo $time; ?>" />
		<input name="pass" type = "hidden" value="<?echo $pass; ?>" />
		<button type="submit">送信</button>
		</form>
		<hr>
		<?php
				if(ctype_digit($_POST['edit_num'])){
					$edit_num = $_POST['edit_num'];
					$user = $_POST['user'];
					$text = $_POST['text'];
					$time = $_POST['time'];
					$pass = $_POST['pass'];
					//ここまでに、受信した内容を変数に入れておく
					$filedata = file($filename);//$hairetu に　$filenameを代入
					$fp = fopen($filename,'w+');
					foreach($filedata as $line){//配列から1つずつ取り出す
						$data = explode("<>",$line);	//<>出来って配列に
						//echo "check_if|".$data[0]."|".$edit_num."|<hr>";//デバック
						if($data[0] == $edit_num){	//投稿番号が編集番号と同じなら括弧の中の処理
							$text=$edit_num.'<>'.$user.'<>'.$text.'<>'.$time.'<>'.$pass.'<>'."\n";
							fputs($fp,$text);//編集した一行をファイルに追記
						}else{	//元の一行をファイルに追記
							fputs($fp,$line);
						}
					}
					fclose($fp);
				}
		?>
		
		
		
		<h2>投稿一覧</h2>
		<!--2-3追記-->
		
		<?php
			$hairetu = file($filename);//$hairetu に　$filenameを代入
			foreach($hairetu as $line){
				$hairetu2 = explode("<>",$line);//explode		
				echo $hairetu2[0]." ";//出力
				echo '名前 : '.$hairetu2[1].' ';//出力
				echo '投稿日時 : '.$hairetu2[3].'<br/>';//出力
				echo $hairetu2[2]." ";//出力
				echo '<hr/>';
			}
		?>
		</body>
</html>
