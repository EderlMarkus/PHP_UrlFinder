/**
 * fills html table with json-data
 * @param {JSON} json
 */
function fillTable(json) {
  let table = document.getElementsByTagName('table')[0];
  createTableHeadfromArray(table, json);
  createRowsforTable(table, json);
}

/**
 * creates tbody and rows for the table.
 * fills rows with the values from json
 * also checks if json-value is home or foreign url
 * TODO: should be splitted in smaller methods
 *
 * @param {HTMLElement} table
 * @param {JSON} json
 */
function createRowsforTable(table, json) {
  let rows = getSizeOfBiggestArray(json);
  let keys = getKeysFromJson(json);
  let body = document.createElement('tbody');

  //loop through the length of rows and create trs
  for (let i = 0; i < rows; i++) {
    let tr = document.createElement('tr');

    //loop through keys and create tds
    for (let j = 0; j < keys.length; j++) {
      let td = document.createElement('td');
      if (json[keys[j]].length > i) {
        const element = json[keys[j]][i];
        td.setAttribute('class', 'filled');
        td.innerHTML =
          '<a onclick="return postWithValue(this.innerHTML)">' +
          element +
          '</a>';
      } else {
        td.innerHTML = '';
      }
      tr.appendChild(td);
    }

    body.appendChild(tr);
  }

  table.appendChild(body);
}

/**
 * Gets Size of the biggest Array if JSON has Keys with Arrays
 * @param {JSON} json
 * @returns {int} ret: number of biggest array
 */
function getSizeOfBiggestArray(json) {
  let keys = getKeysFromJson(json);
  let ret = 0;

  for (let index = 0; index < keys.length; index++) {
    let currentArrayLength = json[keys[index]].length;
    if (currentArrayLength > ret) {
      ret = currentArrayLength;
    }
  }

  return ret;
}

/**
 * creates thead element for table
 * @param {HTMLElement} table
 */
function createTableHead(table) {
  const tableHead = document.createElement('thead');
  table.appendChild(tableHead);
  return tableHead;
}

/**
 * creates th elements with keys from JSON and append to table
 * @param {HTMLElement} table
 * @param {JSON} json
 */
function createTableHeadfromArray(table, json) {
  let keys = getKeysFromJson(json);
  let headerRow = createTableRowWithArray(keys, true);

  const tableHead = createTableHead(table);
  tableHead.appendChild(headerRow);
}

/**
 * returns keys from JSON
 * @param {JSON} json
 * @returns {Array} keys = keys from json
 */
function getKeysFromJson(json) {
  let keys = [];

  for (i in json) {
    keys.push(i);
  }
  return keys;
}

/**
 * void; Fills HTML Table with values from array
 * @param {HTMLElement} tablecolumn: column where rows shall be appended
 * @param {Array} array
 * @param {boolean} isHeader
 */
function createTableRowWithArray(array, isHeader = false) {
  let cellElement;
  let row = document.createElement('tr');

  array.forEach(element => {
    if (isHeader) {
      cellElement = document.createElement('th');
    } else {
      cellElement = document.createElement('td');
    }
    cellElement.innerHTML = element;
    row.appendChild(cellElement);
  });

  return row;
}
