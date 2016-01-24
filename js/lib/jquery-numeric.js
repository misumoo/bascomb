/**
 * Created by Misumoo on 6/4/2015.
 */

$(function() { //on key up, check anything with class of numbersOnly and replace alpha chars
  $('.numbersOnly').keyup(function () {
    this.value = this.value.replace(/[^0-9\.]/g,'');
  }); //numbersOnly
});