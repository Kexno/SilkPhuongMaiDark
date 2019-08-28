        <link href="public/css/common.css" rel="stylesheet" type="text/css">
        <link href="public/css/contact.css" rel="stylesheet" type="text/css">
        <div class="kontainer" id="page-content">
            <div class="row">
                <iframe class="col-12" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3918.1444624324704!2d106.7643044144685!3d10.876615060311355!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3174d9dc3c2dbdd5%3A0x7abc746ecbc0f96b!2sPAMATRA!5e0!3m2!1svi!2s!4v1564514666217!5m2!1svi!2s" width="600" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>
                <div class="col-12 col-md-6" id="infor">
                    <b>Địa chỉ:</b>
                    <p><?php echo $this->settings['contact'][$this->session->public_lang_code]['address'] ?></p>
                    <b>Văn phòng đại diện:</b>
                    <p><?php echo $this->settings['contact'][$this->session->public_lang_code]['office'] ?></p>
                    <b>Hotline:</b>
                    <p><?php echo $this->settings['contact'][$this->session->public_lang_code]['hotline'] ?></p>
                    <b>Email:</b>
                    <p><?php echo $this->settings['contact'][$this->session->public_lang_code]['email'] ?></p>
                </div>
                <div class="col-12 col-md-6">
                    <h6 class="d-flex justify-content-center">ĐỂ LẠI THÔNG TIN ĐỂ ĐƯỢC TƯ VẤN SỚM NHẤT</h6>
                    <form id="leave_info">
                        <input class="col-12" type="text" name="fullname" placeholder="Họ & tên . . .">
                        <input class="col-12" type="text" name="phone" placeholder="Số điện thoại . . .">
                        <textarea name="content" placeholder="Nội dung"></textarea>
                        <button id="content-submit" class="buttonls" type="submit"><img src="public/images/common/send.svg"></button>
                    </form>
                </div>
            </div>            
        </div>
        <script type="text/javascript">
            $('body').addClass('darkbg textmedium');
        </script>