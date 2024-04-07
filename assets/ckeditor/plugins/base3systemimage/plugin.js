CKEDITOR.plugins.add('base3systemimage',
   {
      requires : ['iframedialog'],
      init : function(editor) {
         var pluginName = 'base3systemimage';
         var mypath = this.path;
         editor.ui.addButton(
            'helloworld.btn',
            {
               label : "My Plug-in",
               command : 'base3systemimage.cmd',
               icon : mypath + 'icons/base3systemimage.png'
            }
         );
         var cmd = editor.addCommand('base3systemimage.cmd', {exec:showDialogPlugin});
         CKEDITOR.dialog.addIframe(
            'base3systemimage.dlg',
            'Hello Title',
            mypath + 'base3systemimage.html',
            400,
            300,
            function(){
            }
         );
      }
   }
);

function showDialogPlugin(e){
   e.openDialog('base3systemimage.dlg');
}