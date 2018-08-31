<?php
use yii\helpers\Html;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\MessageInterface the message being composed */
/* @var $content string main view render result */
?>
<?php $this->beginPage() ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Rode &amp; Associates</title>
<?php $this->head() ?>
</head>
<body style="font-family:Verdana">
<?php $this->beginBody() ?>
<div>
    <?php echo $content ?>
</div>
<div>
    <b>John S. Lottering | Head: Research &amp; Publications | Rode &amp; Associates</b><br>
    Office:  021 946 2480 | Cell: 083 602 5522 | Fax: 021 946 1238<br>
    E-mail <a href="mailto:john@rode.co.za">john@rode.co.za</a>
</div>
<div>
    <img src="http://rodeonlinesurveys.co.za/web/images/logo_g.gif">
</div>
<div style="size: 6px;">
    This e-mail may contain confidential information and may be legally privileged and is intended only for the person to whom it is addressed. If you are not the intended recipient, you are notified that you may not use, distribute or copy this document in any manner whatsoever. Kindly also notify the sender immediately by telephone, and delete the e-mail. Rode &amp; Associates does not accept liability for any damage, loss or expense arising from this e-mail and/or accessing any files attached to this e-mail. Disclaimer is deemed to form part of the content of this email in terms of Section 11 of the Electronic Communications &amp; Transactions Act, 25 of 2002.
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>