async function loadSchema(schema, id) {
  let uri = "http://localhost:8081/api/v1/" + schema;
  if (id) {
    uri += "/" + id;
  }
  const response = await fetch(uri);
  const data = await response.json();
  console.log("API call response", uri, data);
  // Find form/fields and set values?
  // TODO multiple forms?
  let myForm = document.forms[0];
  // html form doesn't support PUT, so need to ajax the submit button;
  console.log(myForm);
  // Find each named field based on data?
  let keys = Object.keys(document.forms[0].elements);
  for (let k of keys) {
    let value = null;
    // Some k will be like items[0][title]
    if (k.includes("[0]")) {
      let parts = k.split("\[0\]");
      let nestedKey = parts[1].substring(1, parts[1].length - 1);
      value = data[parts[0]][0][nestedKey];
    } else {
      value = data[k];
    }
    if (myForm.elements[k] && value) {
      myForm.elements[k].value = value;
    }
  }
}

async function submitForm(schema, form, event) {
  event.preventDefault();
  console.log("submitting", form, event);
  let uri = "http://localhost:8081/api/v1/" + schema;
  let method = "POST"
  if (form.elements._id.value) {
    uri += "/" + form.elements._id.value;
    method = 'PUT'
  }
  const response = await fetch(uri, {
    'method': method,
    'headers': {
      Accept: 'application.json',
      'Content-Type': 'application/json'
    },
    'body': new FormData(form)
  });
  const data = await response.json();
  console.log(data);
}
