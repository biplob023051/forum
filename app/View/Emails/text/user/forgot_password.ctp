<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Emails.text
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
?>
Dear <?php echo $name; ?>,

Your reset password request has been processed.

Your password reset request was for 
Your Email : <?php echo $email; ?>

To continue reset, please click following URL:
<?php echo $loginUrl . 'member/users/reset_password/' . $user_id . '/' . $resetpassword; ?>


If you don't send this request, just ignore this email.

You don't need to reply this email.
