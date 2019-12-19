import { expect } from 'chai'
import { shallowMount } from '@vue/test-utils'
import Page from '@/views/Home.vue'

describe('页面测试', () => {
  it('页面正常渲染', () => {
    const msg = '加入联盟'
    const wrapper = shallowMount(Page)
    expect(wrapper.text()).to.include(msg)
  }),

  it('素材列表正常渲染', () => {
    const wrapper = shallowMount(Page)
    expect(wrapper.classes('el-card')).toBe(true)
  })
})
