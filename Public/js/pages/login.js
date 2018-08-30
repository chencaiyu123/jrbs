$(function(){
	$('#entry').click(function(){

        var name 	= $('#adminName').val();
        var pwd 	= $('#adminPwd').val();

		if(name ==''){
			$('.mask,.dialog').show('123');
            $('.lt-title').html('警告');
			$('.dialog .dialog-bd p').html('请输入管理员账号');
		}else if(pwd ==''){
            $('.mask,.dialog').show('123');
            $('.lt-title').html('警告');
            $('.dialog .dialog-bd p').html('请输入管理员密码');
		}else{
			$('.mask,.dialog').hide();
			$('.form-content').hide(700);
			$('#header').html('<span><span class="icon-spin icon-cogs"></span>登录中...</span>');
			$.ajax({
				type:'post',
                url : "/Index/",
                async:false,
                data:{'name':name,'password':pwd},
                dataType: 'json',
                success:function (data) {

                },
				error:function () {

                }
			});
		}
	});
});
