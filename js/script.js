/**
 * submits form with the given value
 * @param {string} value
 * TODO: "https://" should not be hardcoded but rather
 * depending on if url is really https.
 */
function postWithValue(value) {
  const form = document.getElementsByTagName('form')[0];
  form.getElementsByTagName('input')[0].value = value;

  form.submit();
}
