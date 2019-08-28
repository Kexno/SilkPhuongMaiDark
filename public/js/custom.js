    $(document).ready(function(){
        $('.modal button.buttonls').on('click',function(e){
            e.preventDefault();
            $('span.text-danger').remove();
            $.ajax({
                url : base_url+'product/order',
                type: "POST",
                data: $('#form_order').serialize(),
                dataType: "JSON",
                success: function(data)
                {
                    toastr[data.type](data.message);
                    if(data.type=='warning'){
                        $.each(data.validation,function(k,v){
                                $('[name="'+k+'"]').after(v);
                        });
                    }else{
                        $('#form_order')[0].reset();
                    }
                }
            });
        });
        $('.dropdown-submenu > a').on("click", function(e) {
            var submenu = $(this);
            $('.dropdown-submenu .dropdown-menu').removeClass('show');
            submenu.next('.dropdown-menu').addClass('show');
            e.stopPropagation();
        });

        $('.dropdown').on("hidden.bs.dropdown", function() {
                    // hide any open menus when parent closes
                    $('.dropdown-menu.show').removeClass('show');
                });
    });
    function chooseLang(lang){
        var cur_ = document.URL;
        window.location.href = cur_ + "?lang=" + lang;
    }
    $(document).ready(function(){
        $('#content-submit').on('click',function(e){
            e.preventDefault();
            $('span.text-danger').remove();
            $.ajax({
                url : base_url+'contact/submit',
                type: "POST",
                data: $('#leave_info').serialize(),
                dataType: "JSON",
                success: function(data)
                {
                    toastr[data.type](data.message);
                    if(data.type=='warning'){
                        $.each(data.validation,function(k,v){
                            $('[name="'+k+'"]').after(v);
                        });
                    }else{
                        $('#leave_info')[0].reset();
                    }
                }
            });
        });
        $('#btn_footer').on('click',function(e){
            e.preventDefault();
            $('span.text-danger').remove();
            $.ajax({
                url : base_url+'newsletter/submit',
                type: "POST",
                data: $('#form_footer').serialize(),
                dataType: "JSON",
                success: function(data)
                {
                    toastr[data.type](data.message);
                    if(data.type=='warning'){
                        $.each(data.validation,function(k,v){
                            $('[name="'+k+'"]').after(v);
                        });
                    }else{
                        $('#form_footer')[0].reset();
                    }
                }
            });
        });
    });