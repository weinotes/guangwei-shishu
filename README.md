# 光卫诗书 (Guangwei Shishu)

[![WordPress Theme](https://img.shields.io/badge/WordPress-Theme-blue.svg)](https://wordpress.org/)
[![License](https://img.shields.io/badge/License-GPL%20v2%2B-blue.svg)](https://www.gnu.org/licenses/gpl-2.0.html)
[![Version](https://img.shields.io/badge/Version-1.4.7-green.svg)](https://github.com/weinotes/guangwei-shishu/releases)

> 王光卫诗书词专用WordPress主题 - 传承中华文化，展现诗词之美

## 主题简介

光卫诗书是一款专为诗词书法展示设计的WordPress区块主题（Block Theme），融合中国古典美学与现代Web技术。主题采用宣纸白、墨黑、朱砂红、竹青、藤黄等传统色彩，配合宋体、书法字体，营造浓厚的中国文化氛围。

## 核心特性

### 🎨 中国传统美学
- **传统色彩系统**：宣纸白、墨黑、朱砂红、竹青、藤黄、水墨灰
- **中文字体支持**：Noto Serif SC（宋体）、Noto Sans SC（黑体）、Ma Shan Zheng（书法）
- **诗词排版优化**：竖排支持、传统行距、印章样式

### 🔍 内置SEO/GEO优化
无需依赖第三方SEO插件，主题内置完整的搜索引擎优化功能：

- **Schema.org 结构化数据**：Article、WebSite、Organization、BreadcrumbList
- **Open Graph / Twitter Card**：社交媒体分享优化
- **智能Meta标签**：自动提取摘要、关键词
- **XML站点地图**：自动生成并自动Ping搜索引擎
- **Canonical链接**：防止重复内容

### 📱 响应式设计
- 完美适配桌面、平板、手机
- 图片懒加载优化
- 打印样式优化

### ⚡ 性能优化
- 轻量级代码，无冗余依赖
- 图片懒加载
- 字体完全本地化，使用系统字体栈
- 优化的CSS和JavaScript

## 安装方法

### 方法一：通过WordPress后台安装
1. 下载主题ZIP文件
2. 进入WordPress后台 → 外观 → 主题 → 添加新主题 → 上传主题
3. 选择下载的ZIP文件并安装
4. 点击"启用"

### 方法二：通过FTP安装
1. 解压主题文件
2. 将文件夹上传到 `/wp-content/themes/` 目录
3. 进入WordPress后台 → 外观 → 主题
4. 找到"光卫诗书"并点击"启用"

## 主题设置

### 自定义Logo
1. 进入WordPress后台 → 外观 → 自定义 → 站点身份
2. 上传您的Logo图片

### 导航菜单
1. 进入WordPress后台 → 外观 → 菜单
2. 创建新菜单并分配到"主导航"位置

### 固定链接设置
主题会自动移除分类和标签的URL前缀（/category/、/tag/），使URL更简洁美观。

## 文件结构

```
guangwei-shishu/
├── assets/
│   ├── css/           # 样式文件
│   ├── js/            # JavaScript文件
│   └── images/        # 图片资源
├── inc/
│   ├── schema.php     # Schema.org结构化数据
│   ├── opengraph.php  # Open Graph元标签
│   └── sitemap.php    # XML站点地图
├── parts/
│   ├── header.html    # 页眉模板部件
│   └── footer.html    # 页脚模板部件
├── templates/
│   ├── index.html     # 首页模板
│   ├── single.html    # 文章页模板
│   ├── archive.html   # 归档页模板
│   ├── page.html      # 页面模板
│   ├── 404.html       # 404页面模板
│   └── search.html    # 搜索结果模板
├── languages/         # 翻译文件
├── style.css          # 主题样式
├── theme.json         # 主题配置
├── functions.php      # 功能文件
└── README.md          # 说明文档
```

## 浏览器支持

- Chrome / Edge（最新2个版本）
- Firefox（最新2个版本）
- Safari（最新2个版本）
- 移动端浏览器

## 更新日志

### 1.4.7 (2024-04-28)
- 优化页眉布局，移除背景框
- 修复桌面/移动端菜单显示
- 添加上一篇/下一篇导航
- 添加相关文章功能
- 中国传统风格设计
- 内置SEO/GEO优化
- 响应式布局
- 区块编辑器支持

### 1.0.0 (2024-04-28)
- 初始版本发布

## 开源协议

本主题采用 [GNU General Public License v2.0 或更高版本](https://www.gnu.org/licenses/gpl-2.0.html) 开源协议。

```
光卫诗书主题 - 专为诗词书法展示设计的WordPress主题
Copyright (C) 2024 Davey

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
```

## 作者信息

- **作者**: Davey
- **邮箱**: wgwcko@gmail.com
- **网站**: https://www.guangweiblog.com
- **GitHub**: https://github.com/weinotes/guangwei-shishu
- **诗词站点**: https://shishu.guangweiblog.com

## 致谢

- [WordPress](https://wordpress.org/) - 开源内容管理系统
- 系统字体栈 - PingFang SC、Microsoft YaHei 等中文字体

## 反馈与支持

如果您在使用过程中遇到问题或有改进建议，欢迎通过以下方式联系：

- 在 [GitHub Issues](https://github.com/weinotes/guangwei-shishu/issues) 提交问题
- 发送邮件至 wgwcko@gmail.com

---

**传承中华文化，展现诗词之美**