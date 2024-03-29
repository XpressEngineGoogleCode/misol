/**
 * popup으로 열렸을 경우 부모창의 위지윅에디터에 select된 멀티미디어 컴포넌트 코드를 체크하여
 * 있으면 가져와서 원하는 곳에 삽입
 **/
var selected_node = null;
function getCode() {
    // 부모 위지윅 에디터에서 선택된 영역이 있는지 확인
    if(typeof(opener)=='undefined') return;

    // 부모창의 선택된 객체가 img가 아니면 pass~
    var node = opener.editorPrevNode;
    if(!node || node.nodeName != 'IMG') return;

    selected_node = node;

    // 이미 정의되어 있는 변수에서 데이터를 구함
    var freeuse_use_mark = node.getAttribute('freeuse_use_mark');
    var freeuse_allow_commercial = node.getAttribute('freeuse_allow_commercial');
    var freeuse_allow_modification = node.getAttribute('freeuse_allow_modification');

    // form문에 적용
    var fo_obj = xGetElementById('fo');

    if(freeuse_use_mark == 'Y') fo_obj.freeuse_use_mark.selectedIndex = 0; 
    else fo_obj.freeuse_use_mark.selectedIndex = 1;

    if(freeuse_allow_commercial == 'Y') fo_obj.freeuse_allow_commercial.selectedIndex = 0; 
    else fo_obj.freeuse_allow_commercial.selectedIndex = 1;

    if(freeuse_allow_modification == 'Y') fo_obj.freeuse_allow_modification.selectedIndex = 0; 
    else if(freeuse_allow_modification== 'N')  fo_obj.freeuse_allow_modification.selectedIndex = 1;
    else fo_obj.freeuse_allow_modification.selectedIndex = 2;
}

/* 추가 버튼 클릭시 부모창의 위지윅 에디터에 인용구 추가 */
function insertCode() {
    if(typeof(opener)=='undefined') return;

    var fo_obj = xGetElementById('fo');

    var freeuse_use_mark = fo_obj.freeuse_use_mark.options[fo_obj.freeuse_use_mark.selectedIndex].value;
    var freeuse_allow_commercial = fo_obj.freeuse_allow_commercial.options[fo_obj.freeuse_allow_commercial.selectedIndex].value;
    var freeuse_allow_modification = fo_obj.freeuse_allow_modification.options[fo_obj.freeuse_allow_modification.selectedIndex].value;

    var content = '';

    var style = "width:90%; margin:20px auto 20px auto; height:50px; border:1px solid #c0c0c0; background: transparent url('./modules/editor/components/freeuse_license/freeuse_logo.gif') no-repeat center center;";

    var text = '<br /><img editor_component="freeuse_license" freeuse_use_mark="'+freeuse_use_mark+'" freeuse_allow_commercial="'+freeuse_allow_commercial+'" freeuse_allow_modification="'+freeuse_allow_modification+'" style="'+style+'" src="./common/tpl/images/blank.gif" alt="freeuse" /><br />';

    if(selected_node) {
        selected_node.setAttribute('freeuse_use_mark', freeuse_use_mark);
        selected_node.setAttribute('freeuse_allow_commercial', freeuse_allow_commercial);
        selected_node.setAttribute('freeuse_allow_modification', freeuse_allow_modification);
    } else {
        opener.editorFocus(opener.editorPrevSrl);
        var iframe_obj = opener.editorGetIFrame(opener.editorPrevSrl)
        opener.editorReplaceHTML(iframe_obj, text);
    }
    opener.editorFocus(opener.editorPrevSrl);
    window.close();
}

xAddEventListener(window, 'load', getCode);
