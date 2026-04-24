function (doc) {
  if (! doc.hasOwnProperty('visites')) { return }

  var visites = 0;

  if (doc.hasOwnProperty('visites_nb')) {
    visites = doc.visites_nb;
  } else {
    visites = doc.visites ? JSON.parse(doc.visites).length : 0;
  }

  emit([doc._id.substring(0, 1 + parseInt(doc._id.substring(0, 1))), doc.user_id, doc.responsable_nom, doc._id], [1, visites]);
}
