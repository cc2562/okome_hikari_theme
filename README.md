# 稻曦（okome hikari）

![稻曦（okome hikari）](screenshot.png)
一个基于 TTDF 框架构建的现代化 Typecho 主题，专注于简洁、流畅与可配置的体验。内置页面转场、动态配色系统、图片懒加载、代码高亮、评论增强与音乐播放器等功能，开箱即用，亦可按需扩展。

## 运行环境

- `PHP 8.1+`
- `Typecho 1.2+`

## 安装

- 将主题目录放入 `usr/themes/okome_hikari`
- 进入 Typecho 后台 → `外观` → 选择并启用“稻曦（okome hikari）”
- 可选：安装并启用友链插件 `Links`
## 功能亮点

- 动态配色系统：自定义主色/中性色/强调色等，自动适配浅色/深色
- 流畅转场与表单增强：集成 `Swup` 及其 `Forms/Scroll/Progress/Scripts` 插件
- 图片懒加载与模糊占位
- 代码高亮
- 音乐播放器：集成 `APlayer`，支持 `Meting API`
- 友链页面

## 快速配置

在主题设置中可配置以下选项（位于后台 → 外观 → 主题设置）：

- 侧边栏图片与描述文本：用于个人信息展示
- 友链页链接：跳转到友链独立页面
- 关于页链接：跳转到自定义的“关于”独立页面
- 加载占位图：用于图片懒加载的首帧占位
- Meting API 地址：为 `APlayer` 提供曲库接口
- PJAX 回调：在页面转场完成后执行的自定义脚本内容
- 颜色设置：主色、次色、强调色、中性色，以及色调强度与全局圆角

## 自定义字段（文章）

- `FeaturedImage`：文章特色图字段，用于在列表与正文头部展示封面图。
- 在撰写文章时添加该字段（值为图片地址）；列表与正文组件会自动读取与懒加载。

## 页面与组件

- 主页：`index.php` → `Home`
- 文章页：`post.php` → `Post` + `PrevPostWidget` + `Comment`
- 独立页：`page.php` → `Post` + `Comment`
- 友链页：`links.php` → `Links`（需 `Links` 插件）

## 开发与样式定制

- 本主题使用 `Tailwind CSS 4` 与 `daisyUI`，已提供生成后的 `tailwindcss.css`
- 如需调整样式或主题配色，可修改 `assets/app.css` 并使用下列命令生成样式：

```bash
npx @tailwindcss/cli -i ./assets/app.css -o ./assets/tailwindcss.css --watch
```

- 依赖安装（可选，用于本地构建）：

```bash
npm install
```

## 鸣谢

- TTDF 框架（YuiNijika）
- Tailwind CSS、daisyUI
- Swup、highlight.js、lazysizes、APlayer
- G主题（季悠然）


## 许可协议

- 使用 `MIT License`（详见 `LICENSE`）
