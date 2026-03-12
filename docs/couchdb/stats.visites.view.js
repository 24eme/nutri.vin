function (doc) {
  if (!doc.visites_nb) { return }
  emit([doc._id.substring(0, 1 + parseInt(doc._id.substring(0, 1))), doc.user_id, doc.domaine_nom, doc._id], [1, doc.visites_nb]);
}
