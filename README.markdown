Problem
=======
 - Images are too large because they are not optimized
 - Users & your bandwidth is wasted for useless metadata
 - local image optimization requires tons of programs / libaries / knowledge

Solution
========
 - *LOSSLESS* size reduction (10-97% size reduction) in the cloud
 - optmizes all images (jpg+png+[gif]) from a given folder

Usage
=====
Optimize a single image or a whole folder in the cloud.

converting gif-s to png-s if specified on the arguments:

Usage:
    php smusher.php /images [options]
    php smusher.php /images/x.png [options]

Options are:
    -q, --quiet                      no output
    -c, --convert-gifs               convert the given gif file or all .gif`s in the given folder


Protection
==========
Any image that returns a failure code, is larger than before, or is empty will not be saved.

Example
=======
    php smusher.php /images
      smushing /images/facebook_icon.png
      2887 -> 132                              = 4%

      smushing /images/social/myspace_icon.png
      3136 -> 282                              = 8%

      smushing /images/dvd/dvd_1.png
      5045 -> 4                                = 0%
      ...

Author
======
[Javier Martinez Fernandez](http://ecentinela.com)  
ecentinela@gmail.com

based on the work of [Michael Grosser](http://pragmatig.wordpress.com)
[smusher ruby/CLI](http://github.com/grosser/smusher)