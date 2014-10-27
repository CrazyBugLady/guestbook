<div class="col-sm-3 col-md-2 sidebar">
				<ul class='userinformations'>
				
					<li><?php echo $User->Nickname; ?></li>
					<li><?php echo $User->getFormattedBirthDate(); ?></li>
					<li><?php echo $User->getAllEntries() . " Entry/-ies"; ?></li>
				</ul>
				<ul class="nav nav-sidebar">
					<li class="active"><a href="index.php?site=profile">Profil</a></li>
					<li><a href="index.php?site=show"><span class='badge pull-right'><?php echo \Guestbook\gb::countAllEntries(); ?></span>Entries</a></li>
					<li><a href="http://google.ch">Use Google</a></li>
				</ul>
			</div>
		<div id="row">
			<div class="col-sm-8">