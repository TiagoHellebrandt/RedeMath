$(document).ready(function(){
    var contentEx = {
        before: "",
        after: "",
        setBefore: function(){
            this.before = $("#ex").html();
        },
        setAfter: function(){
            this.after = $("#ex").html();
        },
        isModificated: function(){
            return this.before!=this.after;
        }
    };
    $("#ex").keyup(function(){
        contentEx.setAfter();
        if(contentEx.isModificated()){
            $.ajax({
                url: "trataCalc.php",
                method: "post",
                data: {e: contentEx.after,ae: contentEx.before},
                cache: false,
                dataType: "json",
                success: function(e){
                    $("#ex").html(e.ex);
                    setCursor(e.cursor);
                }
            });
        }
        
    });
    $("#ex").keydown(function(e){
        contentEx.setBefore();
        if(e.which==13) {
            $("#calcula").submit();
        }
    });
    
    function getAllChildNodes(child) {
        var r = [];
        for(var i=0;i<child.length;i++) {
            if(child[i].childNodes&&child[i].childNodes.length>0) {
                r = r.concat(getAllChildNodes(child[i].childNodes));
            }else {
                r = r.concat(child[i]);
            }
        }
        return r;
    }

    function setCursor(position) {
        if(position>$("#ex").text().length) {
            position = $("#ex").text().length;
        }
        $("#ex").focus();
        var el = document.getElementById("ex");
        var childs = getAllChildNodes(el.childNodes);
        for(var i=0,s=0;i<childs.length;i++){
            if(position-s<=childs[i].length){
                var range = document.createRange();
                var sel = window.getSelection();
                range.setStart(childs[i], (position-s));
                range.collapse(true);
                sel.removeAllRanges();
                sel.addRange(range);
                break;
            }else {
                s += childs[i].length;
            }
        }
    }
    
});

