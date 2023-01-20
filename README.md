# 随机图片
调用百度、360、搜狗、神马、头条图片搜索接口搜索一张图并返回。支持手机和电脑不同关键字搜索。
> 注意：请求太快可能会被搜索引擎封禁或弹验证码哦！

# 请求

> 请求地址：image.php

> 请求方式：GET/POST

| 参数 | 必填 | 说明 | 默认 |
| -- | -- | -- | --|
| search | 是 | 要搜索的图片（例如电脑壁纸） | |
| pesearch | 否 | 手机访问时要搜索的图片（例如手机壁纸）| （使用q参数传入的值）|
| source | 是 | 搜索引擎（可选值：baidu、360、sougou、shenma、toutiao） |  |
| type | 否 | 数据返回类型（可选值参见[type可选值](#type可选值)) | json |
| random | 否 | 随机数范围 | 1,100 |

## type可选值
| 可选值 | 说明 |
| -- | -- |
| json | 压缩的JSON格式 |
| img | 由服务器下载原站图片后再返回给客户端 |
| thumburl | 跳转到搜索引擎保存的图片地址 |
| redirect | 跳转到图片原站 |
| download | 重定向到百度的图片下载API |

# 返回

| 参数 | 数据类型 | 说明 |
| -- | -- | -- |
| url | String | 原站图片链接 |
| thumburl | String | 搜索引擎保存的图片链接 |
| download | String | 百度图片下载接口链接 |
| p | int | |

## 返回成功示例

```json
{
    "url": "http://i0.hdslb.com/bfs/article/fe192e64dae02b5fed42df441e2579c855b51bce.jpg",
    "thumburl": "https://kkimgs.yisou.com/ims?kt=url&at=smstruct&key=aHR0cDovL2kwLmhkc2xiLmNvbS9iZnMvYXJ0aWNsZS9mZTE5MmU2NGRhZTAyYjVmZWQ0MmRmNDQxZTI1NzljODU1YjUxYmNlLmpwZw==&sign=yx:HufPonuXWhp4CgJ-nIqA1HUzjvc=&tv=400_400",
    "download": "https://image.baidu.com/search/down?tn=download&ipn=dwnl&word=download&ie=utf8&fr=result&url=http%3A%2F%2Fi0.hdslb.com%2Fbfs%2Farticle%2Ffe192e64dae02b5fed42df441e2579c855b51bce.jpg&thumburl=https%3A%2F%2Fkkimgs.yisou.com%2Fims%3Fkt%3Durl%26at%3Dsmstruct%26key%3DaHR0cDovL2kwLmhkc2xiLmNvbS9iZnMvYXJ0aWNsZS9mZTE5MmU2NGRhZTAyYjVmZWQ0MmRmNDQxZTI1NzljODU1YjUxYmNlLmpwZw%3D%3D%26sign%3Dyx%3AHufPonuXWhp4CgJ-nIqA1HUzjvc%3D%26tv%3D400_400",
    "p": 41
}
```

## 返回失败

1. 缺少参数或search参数错误则页面会空白
2. random参数错误则传入的参数不会生效
