<?php
/*
 * function:内容模型
 * author:ikscher
 * date:2013-12-12
 */
class Model_content extends CI_Model {
    private $model;
    private $model_tablename;
    private $tbl_prefix;
    private $table_name;
    private $modelid;

    function __construct() {
        parent::__construct();
        $this->tbl_prefix = $this->db->dbprefix;
    }
    
    public function set($modelid) {
        $this->load->driver('cache', array('adapter' => 'file', 'backup' => 'memcached'));
        
		$this->model = unserialize($this->cache->get('model'));
		$this->modelid = $modelid;
		$this->table_name = $this->tbl_prefix.$this->model[$modelid]['tablename'];
		$this->model_tablename = $this->model[$modelid]['tablename'];
	}
    
    public function select($fields,$catid){
        $result = array();
        $sql ="select {$fields} from {$this->table_name} where catid=?";
        $query = $this->db->query($sql,$catid);
        $result = $query->row_array();
        return $result;
    }
    
    /**
	 * 删除内容
	 * @param $id 内容id
	 * @param $file 文件路径
	 * @param $catid 栏目id
	 */
	public function delete($id,$catid = 0) {
		//删除主表数据
        $sql = "delete from {$this->table_name} where id=?";
        $this->db->query($sql,array('id'=>$id));
		
		//删除从表数据
		$this->table_name = $this->table_name.'_data';
		$sql = "delete from {$this->table_name} where id=?";
        $this->db->query($sql,array('id'=>$id));
        
		//重置默认表
		$this->table_name = $this->tbl_prefix.$this->model_tablename;
		//更新栏目统计
		$this->updateCategoryItems($catid,'delete');
	}
    
    /*
     * 更新栏目下的文章数量
     */
    private function updateCategoryItems($catid,$action = 'add',$cache = 0) {
		$this->load->model('admin/model_category');
		if($action=='add') {
			$this->model_category->updateCategory('`items`=`item`+1'," where catid={$catid}");
		}  else {
			$this->model_category->updateCategory('`items`=`item`-1'," where catid={$catid}");
		}
		if($cache) $this->cacheItems();
	}
	
    /*
     * 更新栏目文章数量缓存
     */
	public function cacheItems() {
        $this->load->driver('cache', array('adapter' => 'file', 'backup' => 'memcached'));
        $this->load->model('admin/model_category');
        
		$datas = $this->model_category->getCategories('catid,type,items'," where modelid=$this->modelid");
		$array = array();
		foreach ($datas as $r) {
			if($r['type']==0) $array[$r['catid']] = $r['items'];
		}
		$this->cache->save('category_items_'.$this->modelid, serialize($array));
	}
    
   
}
?>
