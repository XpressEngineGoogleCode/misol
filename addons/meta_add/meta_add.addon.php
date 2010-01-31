<?php
    if(!defined("__ZBXE__")) exit();

    /**
     * @file meta_add.addon.php
     * @brief 기본적인 metatag 입력과 파비콘 경로 입력.
     **/

    // called_position이 before_module_init일때만 실행, 관리자모드에서 작동 안하기
    if($called_position != 'before_module_init' || Context::get('module')=='admin') return;
    
    $value='';
    $document_srl = Context::get('document_srl');
    $oDocumentModel = &getModel('document');
    $oDocument = $oDocumentModel->getDocument($document_srl, $this->grant->manager);
    $contexttext = $oDocument->getSummary(200);
    $contexttext_arr = explode("\n",$contexttext);
    $contexttext='';
    foreach($contexttext_arr as $value) {
        $contexttext=$contexttext.trim($value);
    }
    $value='';
    $tag_list = $oDocument->get('tag_list');
    $nickname = $oDocument->getNickName();

    $meta_keyword='';
    if($tag_list!='') {
        foreach($tag_list as $value) {
            if($meta_keyword=='') {
            $meta_keyword=$value;
            }
            else {
            $meta_keyword=$meta_keyword.','.$value;
            }
        }
        $meta_keyword=sprintf('<meta name="keywords" content="%s" />'."\n",$meta_keyword);
    }
    elseif($addon_info->meta_keyword!='') {
    $meta_keyword=sprintf('<meta name="keywords" content="%s" />'."\n",$addon_info->meta_keyword);
    }

    $meta_decription='';
    if($contexttext!='') {
    $meta_decription=sprintf('<meta name="description" content="%s" />'."\n",$contexttext);
    }
    elseif($addon_info->meta_decription!='') {
    $meta_decription=sprintf('<meta name="description" content="%s" />'."\n",$addon_info->meta_decription);
    }

    $meta_author='';
    if($nickname!='') {
    $meta_author=sprintf('<meta name="author" content="%s" />'."\n",$nickname);
    }
    if($addon_info->meta_author!='') {
    $meta_author=sprintf('<meta name="author" content="%s" />'."\n",$addon_info->meta_author);
    }
  
    $meta_icon='';
    if($addon_info->meta_icon!='') {
    $meta_icon=sprintf('<link rel="shortcut icon" href="%s" />'."\n",$addon_info->meta_icon);
    }
    
    $meta_robot_index='';
    $meta_robot_follow='';
    if($addon_info->meta_robot_index=='1') {
    $meta_robot_index='index';
    }
    elseif($addon_info->meta_robot_index=='2') {
    $meta_robot_index='noindex';
    }

    if($addon_info->meta_robot_follow=='1') {
    $meta_robot_follow='follow';
    }
    elseif($addon_info->meta_robot_follow=='2') {
    $meta_robot_follow='nofollow';
    }

    if($meta_robot_index!='' && $meta_robot_follow!='') {
    $meta_robot=sprintf('<meta name="robots" content = "%s,%s" />'."\n",$meta_robot_index,$meta_robot_follow);
    }


    $meta_add=$meta_robot.$meta_keyword.$meta_decription.$meta_author.$meta_icon;
    Context::addHtmlHeader($meta_add);
    $meta_keyword='';
    $meta_decription='';
    $meta_author='';
    $meta_icon='';
    $meta_robot='';
    $meta_add='';
?>
