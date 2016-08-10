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

Thanks for registration with us.
Your account has been created successfully.

Your login information is as below:
User Name : <?php echo $username; ?>
User Password : <?php echo $password; ?>

Please active your account form following URL:
<?php echo $loginUrl . 'introducer/introducers/activation/' . $username . '/' . $activation; ?>
