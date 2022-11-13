const {messageParse, messageValidate} = require('index.js');

test('parsed error correctly', () => {
    const sample = '02' + '00000F00' + '635E23CC' + '82' + 'FF';
    const buffer = Buffer.from(sample, 'hex');
    let result = [];
    result.push('Internal Sensor Error');
    result.push('Bandwidth Error');
    expect(messageParse(buffer).type).toBe("Error");
    expect(messageParse(buffer).sensorId).toBe("3840");
    expect(messageParse(buffer).content).toStrictEqual(result);
});

test('parsed error correctly for traffic error', () => {
    const sample = '02' + '00000F00' + '635E23CC' + '20' + 'FF';
    const buffer = Buffer.from(sample, 'hex');
    let result = [];
    result.push('Traffic Light Error');
    expect(messageParse(buffer).type).toBe("Error");
    expect(messageParse(buffer).sensorId).toBe("3840");
    expect(messageParse(buffer).content).toStrictEqual(result);
});


test('parsed state correctly', () => {
    const sample = '01' + '00000F00' + '635E23CC' + '01' + 'FF';
    const buffer = Buffer.from(sample, 'hex');
    expect(messageParse(buffer).type).toBe("Traffic Light State");
    expect(messageParse(buffer).sensorId).toBe("3840");
    expect(messageParse(buffer).content).toBe("Red");
});

test("Throw exception on not valid message length", () => {
    const sample = '01' + '00000F00' + '635E23CC' + '01' + 'FF'+'010101';
    const buffer = Buffer.from(sample, 'hex');
    expect(() => {
        messageValidate(buffer)
    }).toThrow('Message has a wrong byte length.')
});

test("Throw exception on not valid type", () => {
    const sample = '03' + '00000F00' + '635E23CC' + '01' + 'FF';
    const buffer = Buffer.from(sample, 'hex');
    expect(() => {
        messageValidate(buffer)
    }).toThrow('Message has a not valid type.')
});

test("Throw exception on not valid content for error type", () => {
    const sample = '02' + '00000F00' + '635E23CC' + '01' + 'FF';
    const buffer = Buffer.from(sample, 'hex');
    expect(() => {
        messageValidate(buffer)
    }).toThrow('Not valid content value for error message.')
});

test("Throw exception on not valid content for light state type", () => {
    const sample = '01' + '00000F00' + '635E23CC' + '82' + 'FF';
    const buffer = Buffer.from(sample, 'hex');
    expect(() => {
        messageValidate(buffer)
    }).toThrow('Not valid content value for state message.')
});

test("Throw exception on not valid Sensor ID", () => {
    const sample = '01' + 'FF000F00' + '635E23CC' + '01' + 'FF';
    const buffer = Buffer.from(sample, 'hex');
    expect(() => {
        messageValidate(buffer)
    }).toThrow('Not valid Sensor ID.')
});

test("Throw exception on not valid end of message", () => {
    const sample = '01' + '00000F00' + '635E23CC' + '01' + '02';
    const buffer = Buffer.from(sample, 'hex');
    expect(() => {
        messageValidate(buffer)
    }).toThrow('Message has a not valid end message.')
});