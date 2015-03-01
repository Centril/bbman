<?php
/*--------------------------------------------
|	file = admin/faq.php
|	description : showes the FAQ
|	--------------
|	copyright = (C) 2005 The Magicasoft Group
|	email = support@magicasoft.net
*-------------------------------------------*/

$questions = array
(
	// general
	'general' => array
	(
		1 => 'Who wrote this software',
		2 => 'What is BBman 2 and what can I do with it',
		3 => 'I have read the FAQ but my problems were not solved, were can I get more assistance',
		4 => 'Why isn\'t X feature available'
	),
	// characters
	'characters' => array
	(
		1 => 'How do I manage the BBed players',
		2 => 'How do I add a player to the blackbook',
		3 => 'How do I delete a player from the blackbook'
	),
	// fields
	'fields' => array
	(
		1 => 'How do I manage the columns/field for the BBed players',
		2 => 'How do I add a column/field for the BBed players',
		3 => 'How do I delete a column for the BBed players',
		4 => 'How do I save date and time information on a BBed player',
		5 => 'What is a string',
		6 => 'What dose the max length of a column/field do',
		7 => 'Which fieldnames are reserved by the system',
		8 => 'I have edited the name of a field, before i edited the blackbook page was displayed correctly, how do i fix this',
		9 => 'I have added a new field but it won\'t be displayed the blackbook page, how do I fix this'
	),
	// easyfind
	'easyfind' => array
	(
		1 => 'How do I disable/enable the easyfind feature',
		2 => 'How do I manage the easyfind specefications',
		3 => 'How do I add an easyfind specefication',
		4 => 'How do I delete an easyfind specefication',
		5 => 'What exactly is easyfind'
	),
	// styles
	'styles' => array
	(
		1 => 'How do I manage the styles',
		2 => 'How do I add a style',
		3 => 'How do I delete a style',
		4 => 'I have seen things like %@loop%,_loop_,_ranks_ and _member_ in the styles, what are these things for'
	),
	// users
	'users' => array
	(
		1 => 'I am an administrator, how do i view the things that the users have done',
		2 => 'How do I reset the user log',
		3 => 'How do I manage the users',
		4 => 'How do I add an user',
		5 => 'How do I delete an user',
		6 => 'What are the user levels'
	),
	// settings
	'settings' => array
	(
		1 => 'Why shouldn\'t I edit the values of guildtax.lasttimeupdate and online.unixtime',
		2 => 'How do I manage the settings'
	),
	// guildtaxes
	'guildtaxes' => array
	(
		1 => 'How do I disable/enable the guildtax feature',
		2 => 'How do I decide how much the tax will be for each user',
		3 => 'How do I decide the time between each tax',
		4 => 'I want to see what I have left to pay to my guild in taxes, were do I do that',
		5 => 'How do I higher the amount of tax an user has to pay',
		6 => 'What happens if an user is inactive',
		7 => 'Were do I see how much an user has to pay',
		8 => 'How do I manage my guild\'s taxes'
	),
	// glist + online
	'Glist and Tibia Online' => array
	(
		1 => 'How do I disable/enable the guildmembers feature',
		2 => 'How do I decide which guild to load the guild members from',
		3 => 'How do I disable/enable of the Tibia online feature',
		4 => 'How do I decide which world to load the online players from',
		5 => 'How do I decide the time between each online update',
		6 => 'I have the Tibia Online feature disabled but why won\'t Glist work',
		7 => 'How do i only see the guildmembers that\'s online'
	),
	// Miscellaneous
	'Miscellaneous' => array
	(
		1 => 'How do I disable/enable searching',
		2 => 'How do I disable/enable the alfa feature',
		3 => 'How do I disable/enable sorting',
		4 => 'What exactly is alfa'
	)
);

$answers = array
(
	// general
	1 => "This software is produced and copyrighted by the Magicasoft Group.
	It can be downloaded freely at our homepage (www.magicasoft.net).
	The copyright notice must remain,if you have a good reason
	to remove it you may contact us and if we aprove the
	copyright notice removal then your homepage will be listed
	at our homepage, in the products license agreement page.
	You are not allowed to distrubute,sell the software in anyway.
	Modification of BBman2 is at your own sole risk.",

	2 => 'BBman2 is a so called blackbook manager which you can use for 
	listing your enemies and what to do with them.
	In BBman2 you can customize the look of your blackbook totaly with
	what we call styles.
	If you are using Tibia as your role playing game then you can see
	if the players are online and you can have a guildmember list.
	You can also manage guildtaxes, users, fields, settings
	and something we call easyfind.
	You may search for BBed players in an alphabetical way
	and you may preform different types of searching.
	There are much more things to do withit but it to much to be
	explained here.',

	3 => 'Feel free to contact us on our forums at our 
	homepage ( www.magicasoft.net ).',

	4 => "Feature X isn't available beacuse nonone has mentioned to us before
	or beacuse we are tying to implement it.
	Features that may cause security or server problems won't be
	implemented to BBman.
	If you feel that you want this feature for you blackbook then
	feel free to make an request at our forum on magicasoft.net/forum
	or contact us by email ( support@magicasoft.net ).",
	// characters
	5 => 'Click on the "Characters" link to get to the place.
	Then you can edit every field to the value you want it must be
	valid for the type and not to long, 
	if you don\'t know the maxlength and type then you sould contact a
	designer,moderator or administrator.',

	6 => 'Go to the "Characters" page by clicking its link.
	Then you may add a player by entering all the fields avalible with
	valid data and pressing the "Add" button',

	7 => 'Click on the "Characters" link and you will get to the place.
	click the delete link in the BBed player\'s row and confirm.',
	// fields
	8 => 'First you have to click on the "Fields" link to get to the place.
	Then you may change the name of the field and its type
	and the maxlength.
	Please do not change the "Name" field\'s name,
	if you do many of the functions will not be able to work correctly',

	9 => 'Go to the "Fields" page by clicking its link.
	Then you may add a field by entering its name, type and max length.',

	10 => 'Go to the "Fields" page by clicking its link.
	click the delete link in the field\'s row and confirm.',

	11 => 'You will simply use strings instead.
	The reason for not having date and time types is that
	all the database types dosen\'t support them.',

	12 => 'A string is series of bytes.',

	13 => 'The maxlength of a decides how many
	bytes or numbers you may have at most.',

	14 => 'The id and Online fields are reserved by the system.',

	15 => 'You will have to edit the content style.
	Change the {$field[\'old field name\']} to {$field[\'new field name\']}',

	16 => 'The content hasn\'t been instructed to show it yet.
	You can get the value of the field by adding {$field[\'field name\']}
	somewere in the content style',
	// easyfind
	17 => 'Click on the settings link,
	edit the "easyfind" setting to yes or no,
	update the setting and your done',

	18 => 'First you have to click on the "Easyfind" link to get to the place.
	Then you may change the possible values
	and the field name ( it must correspond to a field )
	Separate each value with a comma.',

	19 => 'Go to the "Easyfind" page by clicking its link.
	Then you may add a easyfind specefication by entering
	a field\'s name( there must be a field with the name you specefied )
	and its values.
	Separate each value with a comma.',

	20 => 'Go to the "Easyfind" page by clicking its link.
	click the delete link in the
	easyfind specefication\'s row and confirm.',

	21 => 'The easyfind feature is a thing that prints out the easyfind style
	with links to every value in an easyfind specefication.
	This link will point to all the BBed players
	with {value} in {field}.',
	// styles
	22 => 'First you have to click on the "Styles" link to get to the place.
	The order id of the style is used for ordering them when displaying
	,it starts with the lowest and end with the highest
	The "no match" style will only be
	displayed when there were no match made in the blackbook page.
	The "guildmemberlist" style will only be displayed on the glist.php
	page and it is the style for the whole guildmember page.
	To edit the content of a style click on the "edit content"
	link at the style\'s row.',

	23 => 'Go to the "Styles" page by clicking its link.
	Then you may add a style by entering its name
	in the Add Style table.
	Note: when you have added the style it will be automaticaly
	be assigned an order id but the style will have no content',

	24 => 'Go to the "Styles" page by clicking its link.
	click the delete link in the style\'s row and confirm.',

	25 => 'These are used by the system to handle the loops that is to be run.
	Please do not remove them since it may cause an error in the system
	If you don\'t want it to be displayed then
	disable the features setting( only the sort, easyfind ,
	alfa and the guildmembers styles will contain these
	the styles have settings that will shut down
	the use of the features and the styles.',
	// users
	26 => 'click on the User log link and you will
	be able to see the time and date of the action 
	and the action itself and the name of the user that did the action.',

	27 => 'Go to the user log by clicking on the "User log" link
	and then clicking on the reset link.',

	28 => 'First you have to click on the "Users" link to get to the place.
	You can now edit the username,password,userlevel of every user.',

	29 => 'Go to the "Users" page by clicking its link.
	Then you may add a user by entering its username and password
	and selecting its userlevel in the Add User table.',

	30 => 'Go to the "Users" page by clicking its link.
	click on the delete link in the user\'s row and confirm.',

	31 => 'everyone can view their own taxes
	manager - can manage the BBed players
	can_view_own_taxes - can only view its own taxes
	designer - can manage fields, easyfind and styles
	moderator - everything exept managing users, viewing the user log
	admin - an administrator can administrate everything',
	// settings
	32 => 'beacuse these are filled with the
	amount of seconds from januari the 1st 1970 to a specefied time
	It is generated by the system
	and is used for the Tibia online and the guildtax features.',

	33 => 'Click on the Settings link.
	Now you may do your changes and update them but pressing "Edit".',
	// guildtaxes
	34 => 'click on the settings link,
	edit the "guildtax" setting to yes or no,
	update the setting and your done',

	35 => 'click on the settings link,set the "guildtax.tax" setting
	to the amount of money you want to have as tax ( use numbers ),
	update the setting and your done',

	36 => 'click on the settings link, set the "guildtax.taxevery" setting
	to the amount of days you want ( use numbers ),
	update the setting and your done',

	37 => 'in the control panel click on the "view your guildtax"link,
	and it will take you to that place.',

	38 => 'use negation and the amount of tax,
	that the user has to pay with be higher than before.
	example : user X has 500 left to pay, now you want him to pay 600,
	so you fill the payed field with -100.
	And edit his guildtax and he will now pay 600 instead.',

	39 => 'If a user is inactive then he won\'t have to pay guildtaxes.
	You can set a user to inactive by changing his inactive field
	to "yes" at the guildtax managing place',

	40 => 'At the guildtax managing page find a text that says "left to pay",
	under it, every user\'s guildtax that is left to pay is listed.',

	41 => 'Click on the "manage guildtaxes" link to get to the place.
	If you want to set an user to inactive/active,
	edit the "inactive" field of the user to yes/no.
	If the user has payed something then write that
	in his/her "payed" field and the system will do the arithmic.
	When you are done, simply press the edit button at the user\'s row.',
	// glist + online
	42 => 'click on the settings link,
	edit the "glist" setting to yes or no,
	update the setting and your done',

	43 => 'click on the settings link,
	edit the "glist.guild" setting to the guild you want in lowercase,
	update the setting and your done',

	44 => 'click on the settings link,
	edit the "online" setting to yes or no,
	update the setting and your done',

	45 => 'click on the settings link,
	edit the "online.world" setting to the world you want in lowercase,
	update the setting and your done',

	46 => 'click on the settings link,
	edit the "online.reftime" setting to the amount of minutes you want
	,update the setting and your done',

	47 => 'Beacause the Glist feature uses the Tibia Online feature,
	to see if a guildmember is online.
	Therefore you have to enable the Tibia Online feature.',
	
	48 => 'To only see the guildmembers that\'s online you have to navigate
	to the following url : /path/glist.php?oo=yes
	So you are viewing the same page but with a querystring with it.
	The post method can also be used, then set "oo" to "yes".',
	// Miscellaneous
	49 => 'Click on the settings link,
	edit the "search" setting to yes or no, update the setting.',

	50 => 'Click on the settings link,
	edit the "alfa" setting to yes or no, update the setting.',

	51 => 'Click on the settings link,
	edit the "sort" setting to yes or no, update the setting.',

	52 => 'The alfa feature will display all the letters in the alfabet,
	and a link ( for each letter ) to the method.
	The method is to get all the BBed players with the specefied letter
	as the first character in the BBed players name.'
);

if(!isset($_GET['qid']))
{
	$i = 1;
	foreach($questions as $cat => $qs)
	{
		echo "<br/><table rules=\"all\" style=\"text-align:center; margin:auto; width:90%; border:1px solid #000000;\">
		<tr><td style=\"background-color:#6384B0; color:#FFFFFF; font-weight:bold;\">{$cat}</td></tr>
		<tr><td style=\"text-align:left; background-color:#FFFFFF;\">";
		foreach($qs as $qid => $q)
		{
			echo"<a class=\"faqlink\" href=\"?mode=faq&aid={$i}&qid={$qid}&group={$cat}\">&nbsp;{$q}?</a><br/>";
			$i++;
		}
		echo '</td></tr></table>';
	}
}
elseif(isset($_GET['aid']) && (int)$_GET['aid'] !== 0 && $_GET['aid'] <= count($answers))
{
	$aid = $_GET['aid'];
	$qid = $_GET['qid'];
	$group = $_GET['group'];
	?>
<br/><table rules="all" style="text-align:center; margin:auto; width:96%; border:1px solid #000000;">
<tr><td style="background-color:#6384B0; color:#FFFFFF; font-weight:bold; text-align:left;">question : <?php echo $questions[$group][$qid] ?>?</td></tr>
<tr><td style="text-align:left; background-color:#FFFFFF;"><pre>answer: <?php echo $answers[$aid] ?></pre></td></tr>
</table>
	<?php
}
?>