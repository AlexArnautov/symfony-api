const typeLights = 1;
const typeError = 2;

let typeMap = new Map();
typeMap.set(1, 'Traffic Light State');
typeMap.set(2, 'Error');

const lightsContent = new Map();
lightsContent.set(1, 'Red');
lightsContent.set(2, 'Yellow');
lightsContent.set(3, 'Green');
lightsContent.set(255, 'Unknown State');

function parseErrorsContent(content) {
    let result = [];
    const INTERNAL_ERROR_MASK = 128;
    const TRAFFIC_LIGHT_ERROR_MASK = 32;
    const BANDWIDTH_ERROR_MASK = 2;
    if (content & INTERNAL_ERROR_MASK) {
        result.push('Internal Sensor Error');
    }

    if (content & TRAFFIC_LIGHT_ERROR_MASK) {
        result.push('Traffic Light Error');
    }

    if (content & BANDWIDTH_ERROR_MASK) {
        result.push('Bandwidth Error');
    }

    return result;
}

function parseContent(buffer) {
    let parsedContent;
    const type = getType(buffer);
    const content = getContent(buffer)
    if (type === typeLights) {
        parsedContent = parseLightsContent(content);
    }

    if (type === typeError) {
        parsedContent = parseErrorsContent(content);
    }
    return parsedContent;
}

function parseLightsContent(content) {
    return lightsContent.get(content)
}


function messageValidate(buffer) {
    if (!Buffer.isBuffer(buffer)) {
        throw new Error("Message not a buffer.");
    }

    if (buffer.byteLength !== 11) {
        throw new Error("Message has a wrong byte length.");
    }

    if (getEndMessage(buffer) !== 255) {
        throw new Error("Message has a not valid end message.");
    }

    if (!typeMap.has(getType(buffer))) {
        throw new Error("Message has a not valid type.");
    }

    const sensorId = getSensorId(buffer)
    if (sensorId < 0 || sensorId > 60000) {
        throw new Error("Not valid Sensor ID.");
    }

    if (getDate(buffer).getTime() < 0) {
        throw new Error("Not valid Timestamp.");
    }

    if (getType(buffer) === typeLights && !lightsContent.has(getContent(buffer))) {
        throw new Error("Not valid content value for state message.");
    }

    if (getType(buffer) === typeError && parseErrorsContent(getContent(buffer)).length === 0) {
        throw new Error("Not valid content value for error message.");
    }
}

function getType(buffer) {
    return buffer.readInt8(0);
}

function getSensorId(buffer) {
    return buffer.readUIntBE(1, 4);
}

function getContent(buffer) {
    return buffer.readUIntBE(9, 1);
}

function getEndMessage(buffer) {
    return buffer.readUIntBE(10, 1);
}

function getDate(buffer) {
    const unixTimeStamp = buffer.readUIntBE(5, 4);
    return new Date(unixTimeStamp * 1000);
}

function formatDate(date) {
    return date.toISOString().split('T')[0] + ' ' + date.toTimeString().split(' ')[0]
}

function messageParse(buffer) {
    return {
        type: typeMap.get(getType(buffer)),
        content: parseContent(buffer),
        sensorId: getSensorId(buffer).toFixed(),
        dateTime: formatDate(getDate(buffer))
    };
}

module.exports = {messageParse, messageValidate}
