<?php
/**************PHP Webľ��ɨ����************************/
/* ����alibaba��Monyer����ɨ�跽���ʹ��룬�ڴ˱�ʾ��л */ 
/* youstar  2012.5.24                                  */ 
/* ɨ��ѡ� ���Ŀ¼����ɨ��                         */
/* ���ļ�ɨ�裺��Ե����ļ������﷨ɨ��                */
/* ˵��������ɨ�跽ʽ���ؼ���ƥ�䡢token��������     */
/* �ؼ���ƥ�䣺ֱ��ƥ���ļ��еĹؼ���                  */
/* token������: ͨ��token���н���PHP                 */
/* ����Ŀ¼ɨ�����޷���������ʾ���ɨ������          */
/* ����ͨ�����ļ��鿴������õ�һЩ���к�����Ϣ��      */
/*******************************************************/
ob_start();
set_time_limit(0);
$username = "admin"; //�����û���
$password = "admin"; //��������
$md5 = md5(md5($username).md5($password));
$version = "simple shell scan";

$realpath = realpath('./');
$selfpath = $_SERVER['PHP_SELF'];
$selfpath = substr($selfpath, 0, strrpos($selfpath,'/'));
define('REALPATH', str_replace('//','/',str_replace('\\','/',substr($realpath, 0, strlen($realpath) - strlen($selfpath)))));
define('MYFILE', basename(__FILE__));
define('MYPATH', str_replace('\\', '/', dirname(__FILE__)).'/');
define('MYFULLPATH', str_replace('\\', '/', (__FILE__)));
define('HOST', "http://".$_SERVER['HTTP_HOST']);
?>
<html>
<head>
<title><?php echo $version?></title>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<style>
body{margin:0px;}
body,td{font: 12px Arial,Tahoma;line-height: 16px;}
a {color: #00f;text-decoration:underline;}
a:hover{color: #f00;text-decoration:none;}
.alt1 td{border-top:1px solid #fff;border-bottom:1px solid #ddd;background:#f1f1f1;padding:5px 10px 5px 5px;}
.alt2 td{border-top:1px solid #fff;border-bottom:1px solid #ddd;background:#f9f9f9;padding:5px 10px 5px 5px;}
.focus td{border-top:1px solid #fff;border-bottom:1px solid #ddd;background:#ffffaa;padding:5px 10px 5px 5px;}
.head td{border-top:1px solid #fff;border-bottom:1px solid #ddd;background:#e9e9e9;padding:5px 10px 5px 5px;font-weight:bold;}
.head td span{font-weight:normal;}
</style>
</head>
<body>
<?php
if(!(isset($_COOKIE['t00ls']) && $_COOKIE['t00ls'] == $md5) && !(isset($_POST['username']) && isset($_POST['password']) && (md5(md5($_POST['username']).md5($_POST['password']))==$md5)))
{
	echo '<form id="frmlogin" name="frmlogin" method="post" action="">�û���: <input type="text" name="username" id="username" /> ����: <input type="password" name="password" id="password" /> <input type="submit" name="btnLogin" id="btnLogin" value="��½" /></form>';
}
elseif(isset($_POST['username']) && isset($_POST['password']) && (md5(md5($_POST['username']).md5($_POST['password']))==$md5))
{
	setcookie("t00ls", $md5, time()+60*60*24*365,"/");
	echo "��½�ɹ���";
	header( 'refresh: 1; url='.MYFILE.'?action=scan' );
	exit();
}
else
{
	setcookie("t00ls", $md5, time()+60*60*24*365,"/");
	$setting = getSetting();
	$action = isset($_GET['action'])?$_GET['action']:"";
	
	if($action=="logout")
	{
		setcookie ("t00ls", "", time() - 3600);
		Header("Location: ".MYFILE);
		exit();
	}
	if($action=="download" && isset($_GET['file']) && trim($_GET['file'])!="")
	{
		$file = $_GET['file'];
		ob_clean();
		if (@file_exists($file)) {
			header("Content-type: application/octet-stream");
   			header("Content-Disposition: filename=\"".basename($file)."\"");
			echo file_get_contents($file);
		}
		exit();
	}
?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tbody><tr class="head">
		<td><?php echo $_SERVER['SERVER_ADDR']?><span style="float: right; font-weight:bold;"><?php echo "<a href='http://www.baidu.com/'>$version</a>"?></span></td>
	</tr>
	<tr class="alt1">
		<td><span style="float: right;"><?=date("Y-m-d H:i:s",mktime())?></span>
			<a href="?action=scan">ɨ��</a> | 
            <a href="?action=single">���ļ�</a> |
			<a href="?action=setting">�趨</a> |
          <a href="?action=logout">�ǳ�</a>
		</td>
	</tr>
</tbody></table>
<br>
   <?php
	if($action=="setting")
	{
		if(isset($_POST['btnsetting']))
		{
			$Ssetting = array();
			$Ssetting['user']=isset($_POST['checkuser'])?$_POST['checkuser']:"php | php? | phtml";
			$Ssetting['all']=isset($_POST['checkall'])&&$_POST['checkall']=="on"?1:0;
			$Ssetting['hta']=isset($_POST['checkhta'])&&$_POST['checkhta']=="on"?1:0;
			setcookie("t00ls_s", base64_encode(serialize($Ssetting)), time()+60*60*24*365,"/");
			echo "������ɣ�";
			header( 'refresh: 1; url='.MYFILE.'?action=setting' );
			exit();
		}
     ?>
<form name="frmSetting" method="post" action="?action=setting">
<FIELDSET style="width:400px">
<LEGEND>ɨ���趨</LEGEND>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="60">�ļ���׺:</td>
    <td width="300"><input type="text" name="checkuser" id="checkuser" style="width:300px;" value="<?php echo $setting['user']?>"></td>
  </tr>
  <tr>
    <td><label for="checkall">�����ļ�</label></td>
    <td><input type="checkbox" name="checkall" id="checkall" <?php if($setting['all']==1) echo "checked"?>></td>
  </tr>
  <tr>
    <td><label for="checkhta">�����ļ�</label></td>
    <td><input type="checkbox" name="checkhta" id="checkhta" <?php if($setting['hta']==1) echo "checked"?>></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>
      <input type="submit" name="btnsetting" id="btnsetting" value="�ύ">
    </td>
  </tr>
</table>
</fieldset>
</form>
<?php
	}
	elseif( $action == "single")
	{
		global $vuls,$scount,$sspent;
		$sdir = isset($_POST['spath'])?$_POST['spath']:MYPATH;
		//$sdir = substr($sdir,-1)!="/"?$sdir."/":$sdir;
		?>
		<form name="frmScan" method="post" action="">
		<table width="100%%" border="0" cellspacing="0" cellpadding="0">
 		 <tr>
    	<td width="35" style="vertical-align:middle; padding-left:5px;">�ļ�·��:</td>
    	<td width="690">
        <input type="text" name="spath" id="path" style="width:600px" value="<?php echo $sdir?>">
        &nbsp;&nbsp;<input type="submit" name="sbtnScan" id="sbtnScan" value="��ʼɨ��"></td>
  		</tr>
		</table>
		</form>
		<?php
			if(isset($_POST['sbtnScan']))
		{
			$start=mktime();
			if(is_file($sdir))
			{
			   $vuls = grammerscan($sdir,1);	
			}
			if(count($vuls) > 0)
			$scount = '����';
			$end=mktime();
			$sspent = ($end - $start);
		?>
<!--��ɨ����-->
<div style="padding:10px; background-color:#ccc">Ŀ���ļ�: <?php echo $sdir?>  | �﷨ɨ�����: <?php echo $scount ?>  | ��ʱ: <?php echo $sspent?> ��</div>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr class="head">
    <td width="100%" align="left">��������:
	</td>
  </tr>
</table>
 <?php		
	 }

if(is_array($vuls))
{
echo '<pre>';
 var_dump($vuls);

}
?>

<?php
	}
	else
	{
		$dir = isset($_POST['path'])?$_POST['path']:MYPATH;
		$dir = substr($dir,-1)!="/"?$dir."/":$dir;
?>
<form name="frmScan" method="post" action="">
<table width="100%%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="35" style="vertical-align:middle; padding-left:5px;">ɨ��·��:</td>
    <td width="690">
        <input type="text" name="path" id="path" style="width:600px" value="<?php echo $dir?>">
        &nbsp;&nbsp;<input type="submit" name="btnScan" id="btnScan" value="��ʼɨ��"></td>
  </tr>
</table>
</form>
<?php
		if(isset($_POST['btnScan']))
		{
			$start=mktime();
			$is_user = array();
			$is_ext = "";
			$list = "";
			
			if(trim($setting['user'])!="")
			{
				$is_user = explode("|",$setting['user']);
				if(count($is_user)>0)
				{
					foreach($is_user as $key=>$value)
						$is_user[$key]=trim(str_replace("?","(.)",$value));
					$is_ext = "(\.".implode("($|\.))|(\.",$is_user)."($|\.))";
				}
			}
			if($setting['hta']==1)
			{
				$is_hta=1;
				$is_ext = strlen($is_ext)>0?$is_ext."|":$is_ext;
				$is_ext.="(^\.htaccess$)";
			}
			if($setting['all']==1 || (strlen($is_ext)==0 && $setting['hta']==0))
			{
				$is_ext="(.+)";
			}
			
			$php_code = getCode();
			if(!is_readable($dir))
				$dir = MYPATH;
			$count=$scanned=0;
            $kcont = 0;
			$gcount = 0;
			scan($dir,$is_ext);
			$end=mktime();
			$spent = ($end - $start);
?>
<!--��ɨ����-->
<div style="padding:10px; background-color:#ccc">ɨ��: <?php echo $scanned?> �ļ� | ����ɨ�跢��: <?php echo $kcount ?> �����ļ� | �﷨ɨ�跢��: <?php echo $gcount ?> �����ļ� | ��ʱ: <?php echo $spent?> ��</div>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr class="head">
    <td width="15" align="center">No.</td>
    <td width="25%">�ļ�</td>
    <td width="15%">����ʱ��</td>
    <td width="20%">ԭ��</td>
    <td width="25%">����</td>
    <td>����</td>
  </tr>
<?php echo $list?>
</table>

<?php
		}
	}
}
ob_flush();
?>
</body>
</html>
<?php

function scan($path = '.',$is_ext)
{
	global $scanned,$count,$list,$gcount,$kcount;
	$ignore = array('.', '..' );
    $dh = @opendir( $path );
	while(false!==($file=readdir($dh)))
	{
		if( !in_array( $file, $ignore ) )
		{ 
			if( is_dir( "$path$file" ) )
			{
                scan("$path$file/",$is_ext); 
			}
			else
			{
				$current = $path.$file;
				if(MYFULLPATH==$current) continue;
				if(!preg_match("/$is_ext/i",$file)) continue;
				if(is_readable($current))
				{
					$scanned++;	
					$filetime = date('Y-m-d H:i:s',filemtime($current));//�ļ�ʱ��
					$url =  str_replace(REALPATH,HOST,$current); //�ļ�URL���ʵ�ַ
					//�﷨ɨ��
					$vulninfo = grammerscan($current);
					//echo $vulninfo;
					$vulninfo = htmlentities($vulninfo, ENT_QUOTES);
					//�ؼ���ɨ��
					$keywordinfo = keywordscan($current);
					//�Խ�������ж�
					if($vulninfo != '' && $keywordinfo != '')
					{
						$count++;
						$gcount++;
						$kcount++;
						$j = $count % 2 + 1;
						$list.="
	 					<tr class='alt$j' onmouseover='this.className=\"focus\";' onmouseout='this.className=\"alt$j\";'>
						<td>$count</td>
						<td><a href='$url' target='_blank'>$current</a></td>
						<td>$filetime</td>
						<td><table><tr><td><font color=red>$keywordinfo[0]</font></td></tr><tr><td><font color=red>�﷨ʶ��:</font></td></tr></table></td>
						<td><table><tr><td><font color=#090>$keywordinfo[1]</font></td></tr><tr><td><font color=#090>yes</font></td></tr></table></td>
						<td><a href='?action=download&file=$current' target='_blank'>����</a></td>
	  					</tr>";
					}
					elseif( $vulninfo != '' )
					{
						$count++;
						$gcount++;
						$j = $count % 2 + 1;
						$list.="
	 					<tr class='alt$j' onmouseover='this.className=\"focus\";' onmouseout='this.className=\"alt$j\";'>
						<td>$count</td>
						<td><a href='$url' target='_blank'>$current</a></td>
						<td>$filetime</td>
						<td><font color=red>�﷨ʶ��</font></td>
						<td><font color=#090>yes</font></td>
						<td><a href='?action=download&file=$current' target='_blank'>����</a></td>
	  					</tr>";
					}
					elseif( $keywordinfo != '' )
					{
						$count++;
						$kcount++;
						$j = $count % 2 + 1;
						$list.="
	 					<tr class='alt$j' onmouseover='this.className=\"focus\";' onmouseout='this.className=\"alt$j\";'>
						<td>$count</td>
						<td><a href='$url' target='_blank'>$current</a></td>
						<td>$filetime</td>
						<td><font color=red>$keywordinfo[0]</font></td>
						<td><font color=#090>$keywordinfo[1]</font></td>
						<td><a href='?action=download&file=$current' target='_blank'>����</a></td>
	  					</tr>";
					}
				}
			}
	}   }
}
function keywordscan($file)
{
	global $php_code;
	$reason = '';
	$replace=array(" ","\n","\r","\t");
	$content=file_get_contents($file);
	$content= str_replace($replace,"",$content);
	foreach($php_code as $key => $value)
	{
		if(preg_match("/$value/i",$content))
		{
			$reason = explode("->",$key);
			break;
        }
    }
	return $reason;
} 
function getSetting()
{
	$Ssetting = array();
	if(isset($_COOKIE['t00ls_s']))
	{
		$Ssetting = unserialize(base64_decode($_COOKIE['t00ls_s']));
		$Ssetting['user']=isset($Ssetting['user'])?$Ssetting['user']:"php | php? | phtml | shtml";
		$Ssetting['all']=isset($Ssetting['all'])?intval($Ssetting['all']):0;
		$Ssetting['hta']=isset($Ssetting['hta'])?intval($Ssetting['hta']):1;
	}
	else
	{
		$Ssetting['user']="php | php? | phtml | shtml";
		$Ssetting['all']=0;
		$Ssetting['hta']=1;
		setcookie("t00ls_s", base64_encode(serialize($Ssetting)), time()+60*60*24*365,"/");
	}
	return $Ssetting;
}
function getCode()
{
	return array(
	'��������->cha88.cn'=>'cha88\.cn',
	'��������->c99shell'=>'c99shell',
	'��������->phpspy'=>'phpspy',
	'��������->Scanners'=>'Scanners',
	'��������->cmd.php'=>'cmd\.php',
	'��������->str_rot13'=>'str_rot13',
	'��������->webshell'=>'webshell',
	'��������->EgY_SpIdEr'=>'EgY_SpIdEr',
	'��������->tools88.com'=>'tools88\.com',
	'��������->SECFORCE'=>'SECFORCE',
	'��������->eval("?>'=>'eval\((\'|")\?>',
	'���ɴ�������->system('=>'system\(',
	'���ɴ�������->passthru('=>'passthru\(',
	'���ɴ�������->shell_exec('=>'shell_exec\(',
	'���ɴ�������->exec('=>'exec\(',
	'���ɴ�������->popen('=>'popen\(',
	'���ɴ�������->proc_open'=>'proc_open',
	'���ɴ�������->eval($'=>'eval\((\'|"|\s*)\\$',
	'���ɴ�������->assert($'=>'assert\((\'|"|\s*)\\$',
	'Σ��MYSQL����->returns string soname'=>'returnsstringsoname',
	'Σ��MYSQL����->into outfile'=>'intooutfile',
	'Σ��MYSQL����->load_file'=>'select(\s+)(.*)load_file',
	'���ܺ�������->eval(gzinflate('=>'eval\(gzinflate\(',
	'���ܺ�������->eval(base64_decode('=>'eval\(base64_decode\(',
	'���ܺ�������->eval(gzuncompress('=>'eval\(gzuncompress\(',
	'���ܺ�������->eval(gzdecode('=>'eval\(gzdecode\(',
	'���ܺ�������->eval(str_rot13('=>'eval\(str_rot13\(',
	'���ܺ�������->gzuncompress(base64_decode('=>'gzuncompress\(base64_decode\(',
	'���ܺ�������->base64_decode(gzuncompress('=>'base64_decode\(gzuncompress\(',
	'һ�仰��������->eval($_'=>'eval\((\'|"|\s*)\\$_(POST|GET|REQUEST|COOKIE)',
	'һ�仰��������->assert($_'=>'assert\((\'|"|\s*)\\$_(POST|GET|REQUEST|COOKIE)',
	'һ�仰��������->require($_'=>'require\((\'|"|\s*)\\$_(POST|GET|REQUEST|COOKIE)',
	'һ�仰��������->require_once($_'=>'require_once\((\'|"|\s*)\\$_(POST|GET|REQUEST|COOKIE)',
	'һ�仰��������->include($_'=>'include\((\'|"|\s*)\\$_(POST|GET|REQUEST|COOKIE)',
	'һ�仰��������->include_once($_'=>'include_once\((\'|"|\s*)\\$_(POST|GET|REQUEST|COOKIE)',	
	'һ�仰��������->call_user_func("assert"'=>'call_user_func\(("|\')assert("|\')',		
	'һ�仰��������->call_user_func($_'=>'call_user_func\((\'|"|\s*)\\$_(POST|GET|REQUEST|COOKIE)',
	'һ�仰��������->$_POST/GET/REQUEST/COOKIE[?]($_POST/GET/REQUEST/COOKIE[?]'=>'\$_(POST|GET|REQUEST|COOKIE)\[([^\]]+)\]\((\'|"|\s*)\\$_(POST|GET|REQUEST|COOKIE)\[',	
	'һ�仰��������->echo(file_get_contents($_POST/GET/REQUEST/COOKIE'=>'echo\(file_get_contents\((\'|"|\s*)\\$_(POST|GET|REQUEST|COOKIE)',																																					
	'�ϴ���������->file_put_contents($_POST/GET/REQUEST/COOKIE,$_POST/GET/REQUEST/COOKIE'=>'file_put_contents\((\'|"|\s*)\\$_(POST|GET|REQUEST|COOKIE)\[([^\]]+)\],(\'|"|\s*)\\$_(POST|GET|REQUEST|COOKIE)',
	'�ϴ���������->fputs(fopen("?","w"),$_POST/GET/REQUEST/COOKIE['=>'fputs\(fopen\((.+),(\'|")w(\'|")\),(\'|"|\s*)\\$_(POST|GET|REQUEST|COOKIE)\[',
	'.htaccess��������->SetHandler application/x-httpd-php'=>'SetHandlerapplication\/x-httpd-php',
	'.htaccess��������->php_value auto_prepend_file'=>'php_valueauto_prepend_file',
	'.htaccess��������->php_value auto_append_file'=>'php_valueauto_append_file'
	);	
}



///////////////////////////////////////////////////////////////////////////////////////
//�﷨ɨ�貿��
//////////////////////////////////////////////////////////////////////////////////////
    final class Conf{ 
        //�Ƿ��ϸ���飿ͨ�����������Ƿ�������������� 
        /* 
            �ϸ�����޷����������shell 
            a.php 
            file_put_contents("a.txt",base64_decode("base64encode_str")); 
            b.php 
            include("a.txt"); 
             
            Ҳ��鲻�� 
            eval('abcd'); 
             
            ����һsqlע�룿 
        */ 
        public static $strict = false; 
        public static $auto_ignore = false;//�Զ�����ɨ���ļ� 
        public static $auto_record = false;//�Զ���¼ɨ���ļ�������auto_ignoreΪtrueʱ��Ч 
        public static $hash_file = "find_shell_hash"; 
        public static $hash_arr = array();//��ʼ�� 
         
        //���� �� ��Σ�պ������� 
        public static $vul_type_before = array( 
            T_INCLUDE, //include "1.jpg"; ���Ƿ��Ǹ�shell�� 
            T_INCLUDE_ONCE, 
            T_REQUIRE, 
            T_REQUIRE_ONCE, 
        ); 
        //�п�����Σ���ĺ��� 
        public static $vul_func = array( 
            /* 
             * �����ʺ�������Զ��һ��ì�ܣ� 
             * ���������߼��ĺ����Ͷ��⹥�������õĺ������⼸�����޷���ɵ� 
             * ���������Щ�����ǰ�ȫ�ģ���Щ��Σ�յģ���˵���� 
             */ 
            'create_function', 
            'eval', 
            'system', 
            'passthru', 
            'exec', 
            'popen', 
            'proc_open', 
            'move_uploaded_file', 
            'copy', 
            'shell_exec', 
            'assert', 
            'pcntl_exec', 
            'call_user_func', 
            'call_user_func_array', 
            'call_user_method', 
            'call_user_method_array', 
            'php_check_syntax', 
            'phpinfo',//fileupload+local file include 
            'session_start',//session cheat 
            'ftp_connect',//download+LFI 
            'ftp_ssl_connect', 
            //mysql backup shell 
            'array_map',//��Щ������array�����Ƚϳ��˰� 
            'usort', 
            'uasort', 
            'uksort', 
            'array_filter', 
            'array_reduce', 
            'array_diff_uassoc', 
            'array_diff_ukey', 
            'array_udiff', 
            'array_udiff_assoc', 
            'array_udiff_uassoc', 
            'array_intersect_assoc', 
            'array_intersect_uassoc', 
            'array_uintersect', 
            'array_uintersect_assoc', 
            'array_uintersect_uassoc', 
            'array_walk', 
            'array_walk_recursive',//���� 
            'iterator_apply', 
            'mb_ereg_replace', 
            'mb_eregi_replace', 
            'preg_replace',//��������󱨺ܴ� 
            'preg_replace_callback', 
            'register_shutdown_function', 
            'register_tick_function', 
            'ob_start', 
            'unserialize', 
            'xml_set_character_data_handler',//���º�������callback���� 
            'xml_set_default_handler', 
            'xml_set_element_handler', 
            'xml_set_end_namespace_decl_handler', 
            'xml_set_external_entity_ref_handler', 
            'xml_set_notation_decl_handler', 
            'xml_set_processing_instruction_handler', 
            'xml_set_start_namespace_decl_handler', 
            'xml_set_unparsed_entity_decl_handler', 
            'stream_filter_register', 
            'set_error_handler', 
            'register_shutdown_function', 
            'register_tick_function', 
            'ReflectionFunction',//����� ���� ��http://weibo.com/n/%E5%87%AF%E6%96%87�� �ṩ�� �ٺ٣�ţ�� 
            'error_log',//лл ����_Vin ��http://weibo.com/n/%E8%91%A3%E6%96%B9_Vin�� �ṩ��O(��_��)O����~ 
             
            //������Щԭ���ϲ�����ӣ���֮������ӣ����ǵ�������õ������� 
            'file_put_contents',//LFI 
            'file_get_contents', 
            'file', 
            'set_include_path', 
            'virtual', 
            'bzwrite', 
            'dio_write', 
            'fputcsv', 
            'fputs', 
            'ftruncate', 
            'fwrite', 
            'gzwrite', 
            'recode_file', 
            'backticks', 
            'ftp_get', 
            'ftp_exec', 
            'header', 
        ); 
        /* 
        //��δ�õ����� 
        $vul_type = array( 
            'T_DOLLAR_OPEN_CURLY_BRACES',//${ ���ӱ��������﷨ 
        ); 
        //����Ȳ��飬��Ϊ������Դ�����Ƕ��εģ����Լ���������ô����Ҳ���󣬲�������ȫ���� 
        $user_input = array( 
            '$_GET','$_POST','$_REQUEST', 
            '$_FILE','$_SESSION' 
        ); 
        */ 
         
        //��Σ�����ַ� 
        public static $allow_chars = array( 
            '.',//���$a.(30),������Ϊ�����ַ����ɣ�Ŀǰ������û��������ִ�з�ʽ 
            '=',//�����=���Ǹ�ֵ 
            ',',//�ָ��� 
            ';',//�������Ľ����� 
            '+','-','*','/','%','^','&','|','!',//��������� 
        ); 
         
        //��Σ����token���� 
        public static $allow_type = array( 
            T_AND_EQUAL, 
            T_BOOLEAN_AND, 
            T_BOOLEAN_OR, 
            T_CONCAT_EQUAL, 
            T_DEC, 
            T_DIV_EQUAL, 
            T_INC, 
            T_IS_EQUAL, 
            T_IS_GREATER_OR_EQUAL, 
            T_IS_IDENTICAL, 
            T_IS_NOT_EQUAL, 
            T_IS_NOT_IDENTICAL, 
            T_IS_SMALLER_OR_EQUAL, 
            T_LNUMBER, 
            T_LOGICAL_AND, 
            T_LOGICAL_OR, 
            T_LOGICAL_XOR, 
            T_MINUS_EQUAL, 
            T_MOD_EQUAL, 
            T_MUL_EQUAL, 
            T_OPEN_TAG, 
            T_OPEN_TAG_WITH_ECHO, 
            T_OR_EQUAL, 
            T_PLUS_EQUAL, 
            T_RETURN, 
            T_SL, 
            T_SL_EQUAL, 
            T_SR, 
            T_SR_EQUAL, 
            T_START_HEREDOC, 
            T_XOR_EQUAL, 
            T_STRING,// �����Ҫ���vul_func�ĺ�����ʹ�� 
            T_ISSET, 
            T_IF, 
            T_ELSEIF, 
            T_FOR, 
            T_FOREACH, 
            T_ECHO, 
            T_ARRAY, 
            T_EXIT, 
            T_LIST, 
            T_EMPTY, 
            T_CATCH, 
            T_BOOLEAN_AND, 
            T_BOOLEAN_OR, 
            T_WHILE, 
            T_SWITCH, 
            T_UNSET, 
        ); 
         
        //��Ҫ�����Ե�token���� 
        public static $ignore_type = array( 
            T_WHITESPACE, 
            T_COMMENT, 
            T_DOC_COMMENT, 
        ); 
         
    } 
 
//���ص������Ƿ��ж���    
    function check_callable($token){ 
        $vul = array(); 
        $flag = false; 
        for($i=0;$i<count($token);$i++){ 
            if(is_string($token[$i])){ 
                if($token[$i]=='('){ 
                    $tmp = check_harmful($token,$i-1);//ָ��(��֮ǰ 
                    if($tmp && (empty($vul) || $tmp != $vul[count($vul)-1])){ 
                        $vul[] = $tmp; 
                    }  
                } 
                if($token[$i]=='`'){ // 
                    $flag = $flag == true ? false : true; 
                    if($flag) $vul[] = $token[$i+1]; 
                } 
            }else{ //��Ҫ��ⲻ��Ҫ��(���ĺ�����Ʃ��include 
                if(in_array($token[$i][0],Conf::$vul_type_before)){ 
                    if(Conf::$strict){ 
                        if(has_varparam($token,$i+1)) $vul[] = $token[$i]; 
                    }else{ 
                        $vul[] = $token[$i]; 
                    } 
                    continue; 
                } 
            } 
        } 
        return $vul; 
    } 
     
//����Ƿ��ж��⺯������
    function check_harmful($token,$idx){ 
        for($i=$idx;$i>0;$i--){ 
            if(is_array($token[$i])){ 
                if(in_array($token[$i][1],Conf::$vul_func) 
                    || in_array($token[$i][0],Conf::$vul_type_before) ) 
                { 
                    if(Conf::$strict){//�ϸ���� 
                        //�ӡ�(����ʼ 
                        if(has_varparam($token,$idx+1)) return $token[$i]; 
                        return false; 
                    } 
                    return $token[$i]; 
                } 
                if(in_array($token[$i][0],Conf::$ignore_type)) continue; 
                if(in_array($token[$i][0],Conf::$allow_type)) return false; 
                return $token[$i];//$a(); 
            }else{ 
                //$_GET[a](), ${a}()   define("a",something); 
                if($token[$i]==']' || $token[$i]=='}') return $token[$i-1]; 
                 
                if(in_array($token[$i],Conf::$allow_chars)) return false; 
            } 
        } 
        return false; 
    } 
   
//���������  
    function has_varparam($token,$idx){ 
        $bracket = ""; 
        for($i=$idx;$i<count($token);$i++){ 
            if(is_string($token[$i])){ 
                if($token[$i]=="(") $bracket += 1; 
                if($token[$i]==")") $bracket -= 1; 
                if($token[$i]==";") return false; 
            }else{ 
                if($token[$i][0]==T_VARIABLE || $token[$i][0]==T_STRING) return true; 
            } 
            if($bracket===0) return false; 
        } 
        return false; 
    } 
     
    function read_hash(){ 
        if(!file_exists(Conf::$hash_file)) return array(); 
        $str = file_get_contents(Conf::$hash_file); 
        Conf::$hash_arr = unserialize($str); 
        return true; 
    } 
     
    function add_hash($arr){ 
        $arr = array_merge($arr,Conf::$hash_arr); 
        return file_put_contents(Conf::$hash_file,serialize($arr)); 
    } 
     
//�����ļ�����ָ����׺�ļ��б�
    function file_list($dir,$pattern="") { 
        $arr=array(); 
        if(!is_dir($dir)) { 
            return $arr; 
        } 
        $dir_handle=opendir($dir); 
        if($dir_handle) { 
            while(($file=readdir($dir_handle))!==false) { 
                if($file==='.' || $file==='..') { 
                    continue; 
                } 
                $tmp=realpath($dir.'/'.$file); 
                if(is_dir($tmp)) { 
                    $retArr=file_list($tmp,$pattern); 
                    if(!empty($retArr)) { 
                        $arr = array_merge($arr,$retArr); 
                    } 
                } 
                else { 
                    if($pattern==="" || preg_match($pattern,$tmp)) { 
                        $arr[] =$tmp; 
                    } 
                } 
            } 
            closedir($dir_handle); 
        } 
        return $arr; 
    }
   
function array_multi2single($array)
{
    static $result_array=array();
    foreach($array as $value)
    {
        if(is_array($value))
        {
            array_multi2single($value);
        }
        else  
            $result_array[]=$value;
    }
    return $result_array;
} 

   function grammerscan($file_one,$single=0)
	{
		//global $gcount,$glist;
		$vulninfo = '';
		$vuls = '';
		read_hash(); 
        if(Conf::$auto_ignore)
		{ 
            $hash_one = sha1_file($file_one); 
            if(in_array($hash_one,Conf::$hash_arr))
				continue; 
            else
				$new_hash[] = $hash_one; 
            } 
            $code = file_get_contents($file_one); 

            //$code = mb_convert_encoding($code, "UTF-8"); 

            $code_token = @token_get_all($code); 

            $vuls = check_callable($code_token); 
            if($vuls)
			{ 
				//$ij = $gcount % 2 + 1;
                //var_dump($vuls);
				//$count++;
				$arrayinfo = array_multi2single($vuls);
				foreach($arrayinfo as $info_one)
				{
					if(is_string($info_one))
					{
						$vulninfo .= $info_one;
						$vulninfo .= ':';
					}
				}	
        }
		if($single == 0)
		{
			return $vulninfo;
		}
		else
		{
			return $vuls;
		}
	}
 
?>