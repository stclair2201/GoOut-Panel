$(function(){
	if($("#addbyliuliang")){
		$("#addbyliuliang").click(function(){
			addControl('A',{});
		});
	}
	if($("#addbytime")){
		$("#addbytime").click(function(){
			addControl('B',{});
		});
	}
	initDo(server_sell);
})

function initDo(server_sell){
	for(var i=0;i<server_sell.length;i++){
		addControl(server_sell[i].sell_mode,server_sell[i]);
	}
}

function addControl(mods,data){
	var unit = "&nbsp;MB，销售:&nbsp;";
	var name = 'A'+Date.parse(new Date());
	if(mods=='B'){
		unit = "&nbsp;&nbsp;天，销售:&nbsp;&nbsp;"
		name = 'B'+Date.parse(new Date());
	}
	if(data.value){
		name = data.sell_mode+data.id;
	}
	var html = '<div class="control-group" data-mods="'+mods+'" id="'+name+'">'+
					'<div class="controls">'+
						'<input type="hidden" name="'+name+'_isnew" id="'+name+'_isnew" value="'+(data.value?0:1)+'" >'+
						'<input type="hidden" name="'+name+'_isdel" id="'+name+'_isdel" value="0" >'+
						'<input type="hidden" name="'+name+'_id" value="'+data.id+'">'+
						'<input type="text" class="input span2" name="'+name+'" value="'+(data.value?data.value:"")+'">'+unit+
						'<input type="text" class="input span2" name="'+name+'_price" value="'+(data.price?data.price:"")+'">&nbsp;元'+
						'&nbsp;<a class="btn btn-danger btn-mini" data-id="'+name+'" onclick="delControl(this)" title="删除该信息" id="addbytime"><i class="icon icon-minus"></i></a>'
					'</div>'+
				'</div>';
	$("#price_"+mods+"_div").append(html);
	$("#sell_"+mods).val($("#sell_"+mods).val()+","+name);
}

function delControl(obj){
	var name = $(obj).attr('data-id');
	var mods = $("#"+name).attr("data-mods");
	$("#"+name+"_isdel").val(1);
	$("#"+name).hide();
	if($("#"+name+"_isnew").val() == 1){
		$("#"+name).remove();
		$("#sell_"+mods).val($("#sell_"+mods).val().replace(name,""));
		var reg = new RegExp(",,","g");
		$("#sell_"+mods).val($("#sell_"+mods).val().replace(reg,","));
	}
}


