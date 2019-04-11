===========================================================================
= Project: CMS Content Management System
= File   : readme.txt
= Version: 0.4.8 (2005-03-02)
= Author : Jonathan Beckett
= Email  : jonbeckett@pluggedout.com
= Website: http://www.pluggedout.com/index.php?pk=dev_cms
= Support: http://www.pluggedout.com/development/forums/viewforum.php?f=14
===========================================================================
= Copyright (c) 2004 Jonathan Beckett
= You are free to use and modify this script as long as this header
= section stays intact. This file is part of CMS.
=
= This program is free software; you can redistribute it and/or modify
= it under the terms of the GNU General Public License as published by
= the Free Software Foundation; either version 2 of the License, or
= (at your option) any later version.
=
= This program is distributed in the hope that it will be useful,
= but WITHOUT ANY WARRANTY; without even the implied warranty of
= MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
= GNU General Public License for more details.
=
= You should have received a copy of the GNU General Public License
= along with CMS files; if not, write to the Free Software
= Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
===========================================================================


INSTALLATION INSTRUCTIONS FOR CMS
=================================

n.b. using CMS expects a moderate level of technical know-how. You are
going to be expected to already have PHP and MySQL working on your server.
You are also going to be expected to know how to FTP the CMS files up
to your webspace, and know how to run SQL through your database.

If you get stuck, feel free to post questions to the CMS discussion forum
or alternatively to the comp.lang.php newsgroup.

1. Place the entire folder that you unzipped into a subfolder of your web
   root directory (or the root itself - it doesn't matter).

2. Change the settings in lib/config.php to suit your system. If you get
   stuck feel free to post in the CMS discussion forum (see the link above)

3. Create the database tables required - there is a file called database.sql
   included with CMS - this contains the SQL instructions to run against
   your database. If you are not sure what to do, first check out PHPMySQL
   and if you can't figure that out, post a message asking for help on the
   CMS discussion forum.

4. chmod the uploads, pickup, and repository directories to 777

4. That's it! You should have a working CMS system.



30 SECOND GUIDE TO CMS
======================

Until I get a chance to write some decent instructions on how CMS works,
here's a quick rundown.

Once you have the system up and working, pull up admin.php in your
web browser. Login with "admin" and "password".

The body of your pages should be entered as "content". Each page can have
many pieces of content, and any number of pages can show the same pieces
of content any number of times. Edit a page to see where you add content.
Notice also that pages have a "Template", and PageContent listings
have a "TemplateElement" number.

It goes something like this;

1. A page is associated with a page template.

2. The page template has content placeholders in it
     -e.g.  <!--PAGECONTENT1-->

3. The "PageContent" of a page is a list of the pieces of content
   you want to put on that page, and which element you want to put
   them in - the "element" is the "PAGECONTENT" entry in the page
   template.
   
4. Content and the instance of that content on a page (the "PageContent")
   can have templates too - but they don't have to.
   
5. There are several ways you can swap data into and out of content and/or
   templates when pages are created...
   
   If you make a section like the one illustrated below in a piece of content;
   [metadata]
   field1=value1
   field2=value2
   [/metadata]
   ...you can then put <!--ahmd:field1--> in your content template and it will
   magically get swapped.
   
   The same goes for content metadata (which you can setup in the contenttypes)
   - just put <!--comd:field1--> in your template, and it will get swapped for
   whatever the value is
   
   And finally, page metadata (which you can setup in the pagetypes)
   - just put <!--pgmd:field1--> in your content or template, and it will get
   swapped out.

5. To access a page from CMS, just call index.php?pk=pagekey - where pagekey
   is the key you set on the page you're asking for.


That's basically it as far as the basics are concerned - although of course the
system is designed such that you can build extremely complex systems if you
really try hard...

If you get stuck on anything, just post to the support forum (detailed
below). Please remember that the author has a real job and the rest of
his life to get on with though :)



SUPPORT AND CONTACT INFORMATION
===============================

Please bear in mind that CMS is still not at version 1 - it's very much an
alpha release - so many of the functions don't check anything before they
happen - i.e. be careful, and don't winge when things blow to pieces.

The basic functionality is rock solid - but the admin interface is a rather
dangerous tool. CMS was never designed for newbies.

If you get stuck with something, just visit the following URL to post your
question.

    http://www.pluggedout.com/development/forums/viewforum.php?f=14
    
Joining the forum is free, and your question should get answered within a
day or two normally.


Many thanks if you have read this far into the readme file, and thanks
for trying out CMS!


Regards,

Jonathan Beckett
jonbeckett@pluggedout.com
http://www.pluggedout.com