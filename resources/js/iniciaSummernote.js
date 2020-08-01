// onChange callback
$('#summernote').summernote({
    placeholder: 'Aquí Descripción Conceptual.',
    tabsize: 3,
    height: 200,
    minHeight: null,
    maxHeight: null,
    lang: 'es-ES',
    callbacks: {
      onChange: function(descripcion, $editable) {
        console.log('onChange:', descripcion, $editable);
      }
    }
  });

  // summernote.change
  $('#summernote').on('summernote.change', function(we, descripcion, $editable) {
    console.log('summernote\'s descripcion is changed.');
  });