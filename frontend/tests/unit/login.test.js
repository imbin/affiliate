import { expect } from 'chai'
import { shallowMount } from '@vue/test-utils'
import Page from '@/views/Login.vue'

describe('页面测试', () => {
  it('页面正常渲染', () => {
    const msg = '会员登录'
    const wrapper = shallowMount(Page)
    expect(wrapper.text()).to.include(msg)
  })
})
