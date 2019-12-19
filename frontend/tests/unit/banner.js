import { expect } from 'chai'
import { shallowMount } from '@vue/test-utils'
import Page from '@/components/BannerList.vue'

describe('组件测试', () => {
  it('正常渲染', () => {
    const wrapper = shallowMount(Page, {
      pagination:true, perPage: 12
    })
    expect(wrapper.classes('el-card')).toBe(true)
  })
})
