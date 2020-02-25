const predictionCard = ({location, dateTime, temperature}) => `
<article class="prediction">
    <div class="element">
        <label for="location">Location</label>
        <span id="location">${location}</span>
    </div>
    <div class="element">
        <label for="date">Date</label>
        <span id="date">${dateTime}</span>
    </div>
    <div class="element">
        <label for="temperature">Temperature</label>
        <span id="temperature">${temperature}</span>
    </div>
</article>
`;

const predictionContainer = document.getElementById('predictions');
const datesContainer = document.getElementById('dates');
const scaleContainer = document.getElementById('scales');
let selectDate = null;
let selectScale = null;

const option = value => `
<option value="${value}">${value}</option>
`;

const select = (options, id) => `
<select class="custom-select" id="${id}">
${options}
</select>
`;

fetch('/fixtures')
    .then(r => r.json())
    .then(data => {
        datesContainer.innerHTML = select(data.dates.map(d => {
            return option(d);
        }).join(''), 'select-date');

        enableFilterSelection()
    })
    .catch(e => console.log(e));

function enableFilterSelection() {
    selectDate = document.getElementById('select-date');
    selectDate.addEventListener('change', e => {
        if (e.target.value === '') {
            return;
        }
        fetchPredictions(e.target.value, document.getElementById('select-scale').value);
    })
    scaleContainer.innerHTML = select(['celsius', 'fahrenheit'].map(option).join(), 'select-scale');
    document.getElementById('select-scale').addEventListener('change', e => {
        if (e.target.value === '') {
            return;
        }
        fetchPredictions(document.getElementById('select-date').value, e.target.value);
    })
}

function fetchPredictions(date = '', scale = '')
{
    let url = '/predictions?';
    if (date !== '') {
        url += 'date=' + date + '&';
    }
    if (scale !== '') {
        url += 'scale=' + scale;
    }

    fetch(url)
        .then(r => r.json())
        .then(data => {
            predictionContainer.innerHTML = data.predictions.map(p => {
                let prediction = JSON.parse(p)
                return predictionCard(prediction);
            }).join('');
        })
        .catch(e => console.log(e));
}

fetchPredictions();
