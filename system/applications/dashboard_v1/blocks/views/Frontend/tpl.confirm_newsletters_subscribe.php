<?php if (isset($data->type) && $data->type == 1) : ?>
    <div style="width:100%;height:100%;font-family:Tahoma,sans-serif;color:#333333;font-size:13px;background:#eeeeee;overflow:auto;">
        <table style="border-collapse: collapse; width:100%;">
            <tr>
                <td align="center" bgcolor="#000" valign="middle">
                    <a href="<?= ROOTDOMAIN ?>" title="">
                        <img src="https://bili.vn/images/logo-300x126.png" alt="Bili Leather" style="width: 160px;"/>
                    </a>
                </td>
            </tr>
            <tr>
                <td align="left" bgcolor="#eeeeee" valign="middle">
                    <div style="padding: 20px; text-align:left;">
                        <h3 style="font-size:15px; color:#333; margin: 20px 0 60px 0; text-align: center;">CHÀO MỪNG BẠN ĐÃ THAM GIA!</h3>
                        <p style="margin: 20px 0; font-size: 13px;">
                            Thân mến,<br/>
                            Chào mừng bạn đã gia nhập cộng đồng BILI!<br/><br/>
                            Cảm ơn bạn đã đăng ký bản tin BILI. Hãy sẵn sàng đón chờ các cập nhật mới nhất về xu hướng, hàng mới về và khuyến mại đặc biệt trong thời gian tới nhé!<br/><br/>
                            Để chào mừng, BILI xin dành tặng bạn món quà cho lần mua sắm đầu tiên: mã giảm 10% giảm đến 500.000VNĐ*!<br/><br/>
                            Nhấp <a href="<?= ROOTDOMAIN . '?ref=email' ?>">vào đây</a> để bắt đầu mua sắm và nhập mã: <b><?= $data->coupon ?></b> để được giảm giá khi thanh toán nhé!<br/><br/><br/>
                            Trân trọng,<br/>
                            BILIVN
                        </p>
                        <p style="font-size: 11px; margin: 20px 0;">
                            * Mã chỉ được sử dụng một lần duy nhất
                        </p>
                    </div>
                </td>
            </tr>
        </table>
    </div>
<?php else : ?>
    <div style="width:100%;height:100%;font-family:Tahoma,sans-serif;color:#333333;font-size:13px;background:#eeeeee;overflow:auto;">
        <table style="border-collapse: collapse; width:100%;">
            <tr>
                <td align="center" bgcolor="#000" valign="middle">
                    <a href="<?= ROOTDOMAIN ?>" title="">
                        <img src="https://bili.vn/images/logo-300x126.png" alt="Bili Leather" style="width: 160px;"/>
                    </a>
                </td>
            </tr>
            <tr>
                <td align="left" bgcolor="#eeeeee" valign="middle">
                    <div style="padding: 20px; text-align:left;">
                        <h3 style="font-size:15px; color:#333; margin: 20px 0 60px 0; text-align: center;">CHÀO MỪNG BẠN ĐÃ THAM GIA!</h3>
                        <p style="margin: 20px 0; font-size: 13px;">
                            Thân mến,<br/>
                            Chào mừng bạn đã gia nhập cộng đồng BILI!<br/><br/>
                            Cảm ơn bạn đã đăng ký bản tin BILI. Hãy sẵn sàng đón chờ các cập nhật mới nhất về xu hướng, hàng mới về và khuyến mại đặc biệt trong thời gian tới nhé!<br/><br/>
                            Nhấp <a href="<?= ROOTDOMAIN . '?ref=email' ?>">vào đây</a> để quay trở lại mua sắm với BILI.<br/><br/><br/>
                            Trân trọng,<br/>
                            BILIVN
                        </p>
                    </div>
                </td>
            </tr>
        </table>
    </div>
<?php endif; ?>
