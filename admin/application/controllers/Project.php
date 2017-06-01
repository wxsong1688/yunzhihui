<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Project extends App_Controller
{
    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *      http://example.com/index.php/welcome
     *  - or -
     *      http://example.com/index.php/welcome/index
     *  - or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see http://codeigniter.com/user_guide/general/urls.html
     */

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Project_model');
        $this->load->model('User_model');
    }

    public function index()
    {
        $data        = $_GET;
        $search_data = $this->_getSearchData($data);

        $count                    = $this->Project_model->getListsCount($search_data);
        $pageinfo['total_page']   = ceil($count/$this->pagesize);
        $pageinfo['page']         = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $offset                   = ($pageinfo['page']-1) * $this->pagesize;
        $res = $this->Project_model->getLists($search_data,$offset);

        $list['list']       = $res;
        $list['userinfo']   = $this->userinfo;
        $list['search_data']= $search_data;
        $page['pageinfo']   = $pageinfo;
        $list['total_count']= $count;
        $list['checkusers'] = $this->_getCheckUsers($res);

        $this->load->view('header');
        $this->load->view('adminmenu',$this->userinfo);
        $this->load->view('project/project_list',$list);
        $this->load->view('page',$page);
        $this->load->view('footer');
    }

    public function assigned()
    {
        $search_data['id'] = $_GET['id'];
        $res = $this->Project_model->getLists($search_data);
        $item['prod_info'] = $res[0];
        $item['chushen']   = $this->yzh_conn->from("yzh_user")->where("type", '2')->get()->result_array();
        $this->load->view('header');
        $this->load->view('adminmenu',$this->userinfo);
        $this->load->view('project/project_assigned',$item);
        $this->load->view('footer');
    }

    public function doAssigned()
    {
        
        if($_POST['uid']<1 || !isset($_POST['pid'])){
            echo '<script language="JavaScript">;alert("请选择初审人员！");history.back(-1);</script>';
            exit;
        }
        $data   = array("audio_uid"=>$_POST['uid']);
        $where  = array("id"=>$_POST['pid']);
        $result = $this->yzh_conn->update('yzh_project', $data, $where);
        if($result)
        {
            echo '<script language="JavaScript">;alert("指派成功！");history.back(-1);</script>';
            exit;
        }else{
            echo '<script language="JavaScript">;alert("指派失败！");history.back(-1);</script>';
            exit;
        }
    }

    public function cycle()
    {
        $res = $this->Project_model->getProjectCycle();
        $lists['lists']    = $res;
        $lists['userinfo'] = $this->userinfo;

        $this->load->view('header');
        $this->load->view('adminmenu',$this->userinfo);
        $this->load->view('project/project_cycle',$lists);
        //print_r($lists);
        $this->load->view('footer');
    }


    public function editCycle()
    {   
        $data = $_GET;
        $editData = array();
        if(!empty($data)){
            foreach($data as $k=>$v)
            {
                if(!in_array($k,array(1,3,6,9,12))){
                    continue;
                }
                if(!empty($v)){
                    $editData[$k] = 1;
                }else{
                    $editData[$k] = 0;
                }
            }
            $this->Project_model->editProjectCycle($editData);
        }
        header("Location:/Project/cycle");
    }

    public function renzheng()
    {
        # 每次都验证是否实名认证
        $this->load->model('User_model');
        $where = array('uid'=>$this->uid);
        $userinfo = $this->User_model->getLists($where);
        if($userinfo[0]['id_succ'] == 1)
        {
            header('Location:/Project');exit;
        }

        $res = $this->userinfo;
        $res['delMenu'] = 1;

        //add by huojl 实名认证
        $this->load->library('Public/ApiMobile', null, 'm');
        //session 暂时不可用 可能是权限问题
        //$this->load->library('session');
        //$this->session->set_userdata('createAccount_'.$data['code'], '', 1200);
        $data['code'] = $this->m->getrandomstr(9);
        $data['phone'] = $userinfo[0]['phone'];
        $this->load->view('header');
        $this->load->view('adminmenu',$res);
        $this->load->view('project/renzheng',$data);
        $this->load->view('footer');
    }

    public function detail()
    {
        $search_data['id'] = $_GET['id'];
        $res = $this->Project_model->getLists($search_data);
        $item['prod_info'] = $res[0];

        $this->load->view('header');
        $this->load->view('adminmenu',$this->userinfo);
        $this->load->view('project/project_detail',$item);
        $this->load->view('footer');
    }

    public function pusers()
    {
        $data        = $_GET;
        $search_data = $this->_getUserSearchData($data);

        $count                    = $this->Project_model->getUserProjectsCount($search_data);
        $pageinfo['total_page']   = ceil($count/$this->pagesize);
        $pageinfo['page']         = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $offset                   = ($pageinfo['page']-1) * $this->pagesize;

        $res = $this->Project_model->getUserProjects($search_data,$offset);
        $list['list'] = $res;
        $list['search_data'] = $search_data;
        $page['pageinfo']    = $pageinfo;
        $page['total_count'] = $count;


        if(!empty($res)){
            foreach($res as $v){
                $uinfo[$v['uid']] = $v['uid'];
            }
            $uinfos =  $this->_getUserNames($uinfo);
            $list['usernames'] = $uinfos;
        }
        
        $this->load->view('header');
        $this->load->view('adminmenu',$this->userinfo);
        $this->load->view('project/project_user',$list);
        $this->load->view('page',$page);
        $this->load->view('footer');
    }

    public function addProject()
    {
        $list['userinfo']   = $this->userinfo;
        $where              = array('uid'=>$list['userinfo']['uid']);
        $userinfo           = $this->User_model->getLists($where);
        $res                = $this->Project_model->getProjectCycle();
        $list['cycleinfo']  = $res;
        # 通过身份证信息计算年龄
        $bron = substr($userinfo[0]['identify'],6,8);
        $age = date('Y', time()) - date('Y', strtotime($bron));  
        if (date('m', time()) == date('m', strtotime($bron))){  
          
            if (date('d', time()) > date('d', strtotime($bron))){  
            $age++;  
            }  
        }elseif (date('m', time()) > date('m', strtotime($bron))){  
            $age++;  
        }  
        $list['userinfo']['age'] = $age;
        $this->load->view('header');
        $this->load->view('adminmenu',$this->userinfo);
        $this->load->view('project/project_add',$list);
        $this->load->view('footer');
        
    }

    public function editProject()
    {
        $id = $_GET['id'];
        $data['id'] = $id;

        $res = $this->Project_model->getLists($data);
        $list['item'] = $res;
        # 如果不是本人的项目，不可以编辑
        if($res[0]['tenderee_uid'] != $this->uid){
            echo '<script language="JavaScript">;alert("您无权修改该项目");history.back(-1);</script>;';
            exit();
        }
        $res2               = $this->Project_model->getProjectCycle();
        $list['cycleinfo']  = $res2;

        $this->load->view('header');
        $this->load->view('adminmenu',$this->userinfo);
        $this->load->view('project/project_edit',$list);
        $this->load->view('footer');
    }

    # 债权转让列表
    public function zqzr()
    {
        $search_data = $_GET;
        
        if(!empty($search_data['creditor_name']))
        {
            $search_data['creditor_name'] = trim($_GET['creditor_name']); 
            # 根据用户名获取用户uid
            $this->load->model('User_model');
            $user_where = array('username'=>$search_data['creditor_name']);
            $user_info = $this->User_model->getLists($user_where);
            if(!empty($user_info)){
                $search_data['creditor_id'] = $user_info[0]['uid']; 
            }else{
                $search_data['creditor_id'] = "-1"; 
            }
        }

        $count                    = $this->Project_model->getZqzrCount($search_data);
        $pageinfo['total_page']   = ceil($count/$this->pagesize);
        $pageinfo['page']         = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $offset                   = ($pageinfo['page']-1) * $this->pagesize;

        $res = $this->Project_model->getZqzrLists($search_data,$offset);
        $list['list'] = $res;
        $list['search_data'] = $search_data;
        $page['pageinfo']    = $pageinfo;
        $page['total_count'] = $count;

        if(!empty($res)){
            foreach($res as $v){
                if(!empty($v['creditor_id'])){
                    $uinfo[$v['creditor_id']] = $v['creditor_id'];
                }
                if(!empty($v['buyer_uid'])){
                    $uinfo[$v['buyer_uid']] = $v['buyer_uid'];
                }
            }
            $uinfos =  $this->_getUserNames($uinfo);
            $list['usernames'] = $uinfos;
        }
        

        $this->load->view('header');
        $this->load->view('adminmenu',$this->userinfo);
        $this->load->view('project/project_zqzr',$list);
        $this->load->view('page',$page);
        $this->load->view('footer');
    }

    public function checkProject()
    {
        $id = $_GET['id'];
        $data['id'] = $id;
        $res = $this->Project_model->getLists($data);
        $list['item'] = $res;

        $res2               = $this->Project_model->getProjectCycle();
        $list['cycleinfo']  = $res2;

        # 查看所有的普通管理员，加载审核人员列表
        $this->load->model('User_model');
        $checkuser = $this->User_model->getAllcheckUsers($this->uid);
        $list['checkuser']  = $checkuser;

        # 项目在审核中和初审状态才可以审核
        if(!in_array($res[0]['status'],array(1,2))){
            echo '<script language="JavaScript">;alert("该项目不可审核");history.back(-1);</script>;';
            exit();
        }
        $this->load->view('header');
        $this->load->view('adminmenu',$this->userinfo);
        $this->load->view('project/project_check',$list);
        $this->load->view('footer');
    }


    public function addProjectData()
    {
        # 获取数据
        $data    = $_POST;
        $addData = array();

        # 处理数据/保存数据
        $addData['tenderee_uid']    = intval($this->uid);
        $addData['pro_name']        = trim($data['pro_name']);
        $addData['amount']          = trim($data['amount']);
        $addData['amount']          = str_replace(',','',$addData['amount']);

        $addData['remain_amount']   = $addData['amount'];
        $addData['cycle']           = trim($data['cycle']);
        $addData['status']          = 1;
        $addData['create_time']     = date('Y-m-d H:i:s');

        # 上传图片
        $data['rzpic'] = $this->dealUploadImg($_FILES);
        $dealData = $this->_dealAddData($data);
        $resData  = array_merge($addData,$dealData); 
        $res = $this->Project_model->addProject($resData);
        
        # 跳转到列表(成功跳转到列表，失败跳转到添加页面)
        if($res){
            header('Location:/Project');
        }else{
            echo '<script language="JavaScript">;alert("添加失败");history.back(-1);</script>;';
        }
        exit();
    }

    public function editProjectData()
    {
        # 获取数据
        $data    = $_POST;
        $editData = array();

        # 如果是审核驳回的项目，编辑完直接改为待审核状态
        if($data['status'] == 6){
            $editData['status']             = 1;
        }

        # 处理数据/保存数据
        $editData['pid']            = trim($data['pid']);
        $editData['pro_name']       = trim($data['pro_name']);
        //$editData['type']             = trim($data['type']);
        $editData['amount']         = trim($data['amount']);
        $editData['amount']         = str_replace(',','',$editData['amount']);
        $editData['cycle']          = trim($data['cycle']);

        # 上传图片
        $data['rzpic'] = $this->dealUploadImg($_FILES);
        $dealData = $this->_dealAddData($data);
        $resData  = array_merge($editData,$dealData); 

        $res = $this->Project_model->editProject($resData);
        
        # 跳转到列表(成功跳转到列表，失败后退)
        if($res){
            header('Location:/Project');
        }else{
            echo '<script language="JavaScript">;alert("修改失败");history.back(-1);</script>;';
        }
        exit();
    }

    # 项目信息（初审和复审）（通过或驳回）
    public function checkProjectData()
    {
        # 获取数据
        $data    = $_POST;
        $checkData = array();

        $resData['pid']         = trim($data['pid']);

        # 先检验是否是审核驳回
        if($data['ifreject'] == 1)
        {
            $resData['status']          = 6;
            $resData['reject_reason']   = $data['reject_reason'];
        }else{
            # 复审直接保存状态，不更新其他信息
            if($data['prod_status'] == 2){
                $resData['status']      = 5;
                $resData['raudio_uid']  = $this->uid;
                $resData['online_time'] = date("Y-m-d H:i:s");
            }else{
                # 处理数据/保存数据（初审）
                $checkData['status']    = 2;
                $checkData['pid']       = trim($data['pid']);
                $checkData['type']      = trim($data['type']);
                if($_FILES["uploadFile"]["error"] == 0){
                    $data['rzpic'] = $this->dealUploadImg($_FILES);# 上传图片
                }else{
                    $data['rzpic'] = $data['source_img'];
                }
                $dealData               = $this->_dealAddData($data);
                unset($dealData['financierinfo']);
                # 审核时新增加的数据
                $checkData['year_rate_in']      = trim($data['year_rate_in']);
                $checkData['year_rate_out']     = trim($data['year_rate_out']);
                $checkData['notice']            = trim($data['notice']);
                # 审核人员id:初审的时候要把初审的id和复审的一起存入
                $checkData['audio_uid']         = $this->uid;
                # 判断随机数是否存在
                if(!empty($checkData['type'])){
                    $checkData['pro_num'] = $this->checkRepeat($checkData['type']);
                }else{
                    echo '<script language="JavaScript">alert("参数有误");history.back(-1);</script>;';
                }
                
                
                $resData  = array_merge($checkData,$dealData);
            }
        }

        //复审通过调用汇付 标的信息录入接口
        if($resData['status'] == 5){
            $proInfo = $this->yzh_conn->from("yzh_project")->where("id",$resData['pid'])->get()->result_array();
            $borrowerInfo = $this->yzh_conn->from("yzh_user")->where("uid",$proInfo[0]['tenderee_uid'])->get()->result_array();
            $merCustId = $this->config->config['merCustId'];
            $proId = substr($proInfo[0]['pro_num'],2);
            $borrCustId = $borrowerInfo[0]['hf_usrCustId'];
            $borrTotAmt = sprintf("%.2f",$proInfo[0]['amount']);
            $yearRate = sprintf("%.2f",$proInfo[0]['year_rate_out']/100);
            $retType = '03';
            $bidStartDate = date("YmdHis");
            $bidEndDate = date("YmdHis",strtotime("+7 days"));
            $retAmt = sprintf("%.2f",$proInfo[0]['amount']*(1+$proInfo[0]['year_rate_out']/100*$proInfo[0]['cycle']/12));
            $retDate = date("Ymd",strtotime("+".(string)($proInfo[0]['cycle']*30 + 7)." days"));
            $proArea = '1200';
            $guarCompId = '';
            $guarAmt = '';
            $bgRetUrl = $this->config->config['base_url']."/Project/apiBackAddBidInfo";
            $merPriv = '';
            $reqExt = '';
            $res = $this->chinapnr->objectTypein($merCustId, $proId, $borrCustId, $borrTotAmt, $yearRate, $retType, $bidStartDate, $bidEndDate, $retAmt, $retDate, $proArea, $guarCompId='', $guarAmt='', $bgRetUrl, $merPriv, $reqExt="");
        }
        $res = $this->Project_model->editProject($resData);
        
        # 跳转到列表(成功跳转到列表，失败后退)
        if($res){
            header('Location:/Project');
        }else{
            echo '<script language="JavaScript">alert("审核失败");history.back(-1);</script>;';
        }
        exit();
    }

    //writeLog
    public function writeLog( $fileLog, $content )
    {
        $fp = fopen($fileLog,"a");
        fwrite($fp, $content);
        fclose($fp);
    }

    # 生成产品编号
    function checkRepeat( $type ){
        
        switch ( trim($type) ) {
            case '2': $code = 'A'; break;
            case '3': $code = 'B'; break;
            case '4': $code = 'C'; break;                                           
            default: # code...
            break;
        }

        $defaultW   = 'C';                                      # C：company P：personal  
        $year       = date("Y",time()) - 2014;                  # 2015年为1 之后每年+1 
        $time       = date("md",time());                        # 当月+当日 （1010）
        $this->load->library('Public/ApiMobile', null, 'm');
        $randnum    = $this->m->getrandomstr(3,'randauto');     # 3位不含4的随机数
        $pro_num    = $code.$defaultW.$year.$time.$randnum;
        $res_project = $this->Project_model->getLists( array("pro_num"=>$randnum) );
        if( empty($res_project[0]) ){
            return $pro_num;
        }else{
            $this->checkRepeat($type);
        }
    }

    private function _getUserSearchData($data)
    {
        $searchData = array();
        # 内部融资人只能查看自己的项目投资情况
        if($this->role_type == 7)
        {
            $searchData['tenderee_id'] = intval($this->uid);
        }
        if(!empty($data['id']))
        {
            $searchData['id'] = $data['id']; 
        }
        if(!empty($data['uid']))
        {
            $searchData['uid'] = $data['uid']; 
        }
/*      if(!empty($data['pro_name']))
        {
            $searchData['pro_name'] = urldecode($data['pro_name']); 
        }*/
        /*if(!empty($data['status']))
        {
            $searchData['status'] = intval($data['status']); 
        }
        if(!empty($data['phone']))
        {
            $searchData['phone'] = $data['phone']; 
        }*/
        return $searchData;
    }

    private function _getSearchData($data)
    {
        $searchData = array();
        # 内部融资人只能查看自己发的项目/管理员可以查看所有的项目
        if($this->role_type == 7)
        {
            $searchData['tenderee_uid'] = intval($this->uid);
        # 业务管理员只能查看自己待审/审核的项目
        }elseif($this->role_type == 2) {
            $searchData['audio_uid'] = intval($this->uid);
        # 风险控制管理员只能查看自己待审/审核的项目
        }elseif($this->role_type == 4) {
            $searchData['status'] = 99;
        }else{
            if(!empty($data['tenderee_uid']))
            {
                $searchData['tenderee_uid'] = intval($data['tenderee_uid']); 
            }
        }
        //var_dump($searchData);exit;
        if(!empty($data['pro_id']))
        {
            $searchData['pro_id'] = trim(urldecode($data['pro_id'])); 
        }
        if(!empty($data['amount']))
        {
            $searchData['amount'] = $data['amount']; 
        }
        if(!empty($data['cycle']))
        {
            $searchData['cycle'] = $data['cycle']; 
        }
        if(!empty($data['time_start']))
        {
            $searchData['time_start'] = $data['time_start']; 
        }
        if(!empty($data['time_end']))
        {
            $searchData['time_end'] = $data['time_end']; 
        }
        if(!empty($data['status']) && $data['status']!=99)
        {
            $searchData['status'] = $data['status']; 
        }
        if(!empty($data['check_uname']))
        {
            $searchData['check_uname'] = trim($data['check_uname']); 
            # 根据用户名获取用户uid
            $this->load->model('User_model');
            $user_where = array('username'=>$searchData['check_uname']);
            $user_info = $this->User_model->getLists($user_where);
            if(!empty($user_info)){
                $searchData['check_uid'] = $user_info[0]['uid']; 
            }else{
                $searchData['check_uid'] = "-1"; 
            }
        }
        return $searchData;
    }

    private function _dealAddData($data)
    {
        $addData = array();
        # 公司信息整合
        $companyinfo = array(
            'comp_industry'  => trim($data['comp_industry']),
            'comp_scale'     => trim($data['comp_scale']),
            'comp_guarantee' => trim($data['comp_guarantee'])
        );
        # 产品信息整合
        $projectinfo = array(
            'proj_use'      => trim($data['proj_use']),
            'proj_desc'     => trim($data['proj_desc']),
            'proj_rzpic'    => $data['rzpic']
        );
        # 借款人信息整合
        $financierinfo = array(
            'financier_username'    => trim($data['financier_username']),
            'financier_realname'    => trim($data['financier_realname']),
            'financier_sex'         => trim($data['financier_sex']),
            'financier_year'        => trim($data['financier_year']),
            'financier_mar'         => trim($data['financier_mar'])
        );
        $addData['companyinfo']      = serialize($companyinfo);
        $addData['projectinfo']      = serialize($projectinfo);
        $addData['financierinfo']    = serialize($financierinfo);
        return $addData;
    }


    public function dealUploadImg($file = array())
    {
        if (empty($file["uploadFile"])) { 
            return '';
        }

        //上传路径
        $img_path = dirname(dirname(dirname(__DIR__)))."/files/img/";
        $catalog = "project/";
        $path = $img_path.$catalog;
        
        if(!file_exists($path)) 
        { 
            mkdir($path, 0777); 
        }

        switch ($file['uploadFile']['type']) {
            case 'image/jpeg':
                $type = '.jpg';
                break;
            case 'image/pjpeg':
                $type = '.jpg';
                break;
            case 'image/png':
                $type = '.png';
                break;
            case 'image/gif':
                $type = '.gif';
                break;
            default:
                $type = false;
                break;
        }
        if(!$type){
            echo "不支持该图片类型！";exit;
        }
        $filename = date("YmdHis").rand(1000,9999);
        $file2 = $path.$filename.$type; //图片的完整路径 
        move_uploaded_file($file["uploadFile"]["tmp_name"],$file2);
        return $this->config->config['img_url'].$catalog.$filename.$type;
    }

    private function _getCheckUsers($res){
        # 循环获取审核人员名称
        $userinfo = array();
        if(empty($res)){
            return array();
        }
        foreach($res as $k=>$v)
        {
            if($v['status'] <= 1 ){
                continue;
            }
            if(!empty($v['audio_uid'])){
                $uinfos[$v['audio_uid']] = $v['audio_uid'];
            }
            if(!empty($v['raudio_uid'])){
                $uinfos[$v['raudio_uid']] = $v['raudio_uid'];
            }
        }
        if(empty($uinfos)){
            return array();
        }
        $resinfo = $this->_getUserNames($uinfos);
        return $resinfo;
    }

    private function _getUserNames($uinfos)
    {
        $resinfo = array();
        $this->load->model('User_model');
        $ustr = implode(",",$uinfos);
        $unames = $this->User_model->getUserNamesByUids($ustr);
        if(empty($unames)){
            return array();
        }
        foreach($unames as $val)
        {
            $resinfo[$val['uid']]['username'] = $val['username'];
            $resinfo[$val['uid']]['phone']    = $val['phone'];
        }
        return $resinfo;
    }
}
