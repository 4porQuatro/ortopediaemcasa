import { mount } from 'vue-test-utils';
import expect from 'expect';
import Form from '../../resources/assets/front/js/components/Form.vue';
import moxios from 'moxios';

describe('Form', () =>{

    beforeEach( () => {
        moxios.install();
    });

    afterEach( () => {
        moxios.uninstall();
    });

    it('sends the form to the server', () => {
        let wrapper = mount(Form);

        wrapper.find('#body_input').element.value = 'The times they are changing';
        wrapper.find('#body_input').trigger('input');

        wrapper.find('#send').trigger('click');

        moxios.stubRequest('/contact/update', {
            status: 200,
            response: {
                body: 'The times they are changin'
            }
        });


    });
});