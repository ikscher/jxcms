<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * @package	public function
 * @date	    2013-12-13
 * @author		ikscher
 */

/**
 * 获取请求ip
 *
 * @return ip地址
 */
if ( ! function_exists('getIp'))
{
    function getIp() {
        if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
            $ip = getenv('HTTP_CLIENT_IP');
        } elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
            $ip = getenv('HTTP_X_FORWARDED_FOR');
        } elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
            $ip = getenv('REMOTE_ADDR');
        } elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return preg_match ( '/[\d\.]{7,15}/', $ip, $matches ) ? $matches [0] : '';
    }
}


/**
 * 获取系统信息
 */
if ( ! function_exists('geSysInfo'))
{
	function getSysInfo() {
		$sys_info['os']             = PHP_OS;
		$sys_info['zlib']           = function_exists('gzclose');//zlib
		$sys_info['safe_mode']      = (boolean) ini_get('safe_mode');//safe_mode = Off
		$sys_info['safe_mode_gid']  = (boolean) ini_get('safe_mode_gid');//safe_mode_gid = Off
		$sys_info['timezone']       = function_exists("date_default_timezone_get") ? date_default_timezone_get() : L('no_setting');
		$sys_info['socket']         = function_exists('fsockopen') ;
		$sys_info['web_server']     = $_SERVER['SERVER_SOFTWARE'];
		$sys_info['phpv']           = phpversion();	
		$sys_info['fileupload']     = @ini_get('file_uploads') ? ini_get('upload_max_filesize') :'unknown';
		return $sys_info;
	}
}


/**
* 模板风格列表
* @param integer $info    站点可使用的模板风格列表
* @param integer $disable 是否显示停用的{1:是,0:否}
*/
if ( ! function_exists('getTemplateList'))
{
	function getTemplateList($info, $disable = 0) {
		$list = glob(FRONTPATH.DIRECTORY_SEPARATOR.'*', GLOB_ONLYDIR);
		$arr = $template = array();

        if($info['template']) $template = explode(',', $info['template']);
		
		foreach ($list as $key=>$v) {
			$dirname = basename($v);
			if (!in_array($dirname, $template)) continue;
			if (file_exists($v.DIRECTORY_SEPARATOR.'config.php')) {
				$arr[$key] = include $v.DIRECTORY_SEPARATOR.'config.php';
				if (!$disable && isset($arr[$key]['disable']) && $arr[$key]['disable'] == 1) {
					unset($arr[$key]);
					continue;
				}
			} else {
				$arr[$key]['name'] = $dirname;
			}
			$arr[$key]['dirname']=$dirname;
		}
		return $arr;
	}
}


/**
 * 生成上传附件验证
 * @param $args   参数
 * @param $operation   操作类型(加密解密)
 */

function upload_key($args) {
	$pc_auth_key = md5((COOKIE_AUTHKEY).$_SERVER['HTTP_USER_AGENT']);
	$authkey = md5($args.$pc_auth_key);
	return $authkey;
}


/**
 * 对数据进行编码转换
 * @param array/string $data       数组
 * @param string $input     需要转换的编码
 * @param string $output    转换后的编码
 */
if ( ! function_exists('array_iconv'))
{
    function array_iconv($data, $input = 'gbk', $output = 'utf-8') {
        if (!is_array($data)) {
            return iconv($input, $output, $data);
        } else {
            foreach ($data as $key=>$val) {
                if(is_array($val)) {
                    $data[$key] = array_iconv($val, $input, $output);
                } else {
                    $data[$key] = iconv($input, $output, $val);
                }
            }
            return $data;
        }
    }
}


/**
 * 提示信息页面跳转，跳转地址如果传入数组，页面会提示多个地址供用户选择，默认跳转地址为数组的第一个值，时间为5秒。
 * showmessage('登录成功', array('默认跳转地址'=>'http://www.phpcms.cn'));
 * @param string $msg 提示信息
 * @param mixed(string/array) $url_forward 跳转地址
 * @param int $ms 跳转等待时间
 */
if ( ! function_exists('showMessage')){
    function showMessage($msg, $url_forward = 'goback', $ms = 1250,  $returnjs = '') {
        if(defined('_ADMIN_')) {
            include('system/public/showmessage.php');
        } else {
            //include(template('content', 'message'));
        }
        exit;
    }
}
// ------------------------------------------------------------------------
/* End of file language_helper.php */
/* Location: ./system/helpers/language_helper.php */