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
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

	$siteName = Configure::read('Site.Name');
	$siteType=null;
?>
<!DOCTYPE html>
<html>
    <head>
        <?php echo $this->Html->charset(); ?>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <title><?php echo (strtolower($title_for_layout)=='home'||empty($title_for_layout)==true)?$siteName:($title_for_layout.' - '.$siteName); ?></title>

        <?php
	        echo $this->Html->meta('icon');
	    
	        echo $this->Html->css(array('bootstrap','font-awesome','main'));

	        echo $this->fetch('meta');
	        echo $this->fetch('css');

	        $this->Js->JqueryEngine->jQueryObject = 'jQuery';

	        echo $this->Html->scriptBlock('
	            var projectBaseUrl = "'.Router::url('/', true).'";
	            ', array('inline' => true)
	        );
	    ?>

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
         <script src='https://www.google.com/recaptcha/api.js'></script>
    </head>
    <body>
		<div id="wrapper">
	        <header id="header">
	            <div class="navbar navbar-default" role="navigation" id="logo_part">
	                <div class="container">
		                <div class="navbar-header">
		                	<?php echo $this->html->link($this->html->image('logo.png'),'/',array('class'=>'navbar-brand','escape'=>false));?>
		                </div>
		                
	            		<ul class="list-inline pull-right">
	            			<?php  if(AuthComponent::user('id')):?>
		            			<li><?php echo AuthComponent::user('name');?></li>
		            			<?php if(in_array(AuthComponent::user('role_id'), Configure::read('Role.Backoffice'))) : ?>
		            				<li><?php echo $this->Html->link(__('Dashboard'),array('controller'=>'users','action'=>'home', 'backoffice' => true),array());?></li>
		            			<?php else : ?>
		            				<li><?php echo $this->Html->link(__('Dashboard'),array('controller'=>'users','action'=>'home', 'member' => true),array());?></li>
		            			<?php endif; ?>
		            			<li>
		            				<?php if($this->params['prefix']=='backoffice'):?>
		            					<?php echo $this->html->link(__('LOGOUT'),array('controller'=>'admins','action'=>'logout'));?>
		            				<?php else:?>
		            					<?php echo $this->html->link(__('LOGOUT'),array('controller'=>'users','action'=>'logout', 'member' => true));?>
		            				<?php endif;?>
		            			</li>
		            		<?php else : ?>
		            			<li><?php echo $this->Html->link(__('Login Now!'),array('controller'=>'users','action'=>'home', 'member' => true),array());?></li>
		            			<li><?php echo $this->Html->link(__('Sign Up'),array('controller'=>'users','action'=>'signup', 'member' => true),array());?></li>
		            			<li><?php echo $this->Html->link(__('Forgot Password'),array('controller'=>'users','action'=>'forgot_password', 'member' => true),array());?></li>
	            			<?php  endif;?>
	            		</ul>
	                </div>
	            </div>
	            <div class="container">
            		<?php echo $this->element('navbar/mainmenu');?>
	            </div>
	        </header>
	    	<div class="container">
	            <!-- Main content -->
	            <section class="content">
	                <?php echo $this->Session->flash('auth'); ?>
	                <?php echo $this->Session->flash(); ?> 
	    			<?php echo $this->fetch('content'); ?>
	            </section><!-- /.content -->
	            <?php echo $this->element('sql_dump'); ?>

			</div>
	    </div>

		<footer id="footer">
			<div class="footer-bottom">
				<div class="container">
					<div class="text-center">
						<p>Contact us <a href="mailto:bskamd@gmail.com">bskamd@gmail.com</a></p>
					</div>
				</div>
			</div>
		</footer>

        <div id="busy-indicator">
            <i class="fa fa-spinner fa-spin"></i>
        </div>

        <div id="martexModal" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="martexModalLabel"></h4>
                        <div class="modal-body" id="martexModalBody"></div>
                    </div>
                </div>
            </div>
        </div>

        <?php 
            echo $this->Html->script(array('vendors/jquery/1.11.2/jquery.min','vendors/bootstrap/bootstrap.min'), array('inline' => true));

	        echo $this->fetch('script');

            echo $this->Html->script(array('main', 'common'), array('inline' => true));
            
	        echo $this->Js->writeBuffer(array('safe'=>true));
	    ?>
    </body>
</html>