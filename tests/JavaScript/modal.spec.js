import { mount } from 'vue-test-utils';
import expect from 'expect';
import Modal from '../../resources/assets/front/js/components/Modal.vue';


describe('Modal', () =>{
    it('has a header!', () => {
        let wrapper = mount(Modal);

        expect(wrapper.contains('.modal-header')).toBe(true);
    });
});