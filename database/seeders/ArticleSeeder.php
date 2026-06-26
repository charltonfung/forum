<?php

namespace Database\Seeders;

use App\Models\Article;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    /**
     * 10 篇示範文章，分散在 3 個使用者底下，時間錯開
     */
    public function run()
    {
        $articles = [
            [
                'user_id' => 1,
                'title'   => '歡迎來到 FREE TALK',
                'content' => "這是一個自由暢談的小社群。\n\n你可以發表文章、留言互動、按讚支持喜歡的內容。希望大家在這裡找到有趣的話題與朋友！",
            ],
            [
                'user_id' => 1,
                'title'   => '今天學了 Docker compose',
                'content' => "整個下午都在搞 Docker，一開始覺得超複雜，後來發現只要把 service 用 docker-compose.yml 寫清楚，docker compose up 就一鍵起來，超方便！\n\n之後再也不用裝一堆東西到本機了。",
            ],
            [
                'user_id' => 2,
                'title'   => '推薦一家好吃的拉麵店',
                'content' => "前幾天在中山站附近吃了一家拉麵，湯頭超濃郁，麵條 Q 彈，叉燒入口即化。\n價格也不貴，一碗 280 元，CP 值超高，大家有機會可以去試試！",
            ],
            [
                'user_id' => 2,
                'title'   => 'Vim 為什麼這麼難學',
                'content' => "用了一個禮拜 Vim 還是沒辦法上手，常常按錯鍵直接弄壞東西。\n\n有沒有大神可以推薦一下入門教學？光是要記 hjkl 就花我半天，更別提那些 :wq、:q!、yy、dd...",
            ],
            [
                'user_id' => 3,
                'title'   => '週末爬了陽明山',
                'content' => "天氣不錯，跟朋友去陽明山走走，路線是擎天崗 → 冷水坑。\n\n芒草季很美，記得帶水跟防曬，山上溫差比想像中大。回程吃了山下的金山鴨肉，完美結束一天。",
            ],
            [
                'user_id' => 1,
                'title'   => '為什麼工程師都喜歡用黑色背景',
                'content' => "感覺所有 IDE / terminal 都預設黑底白字，到底為什麼？\n\n後來查了一下，主要是：\n1. 長時間看眼睛比較不累\n2. OLED 螢幕省電\n3. 看起來比較專業 lol",
            ],
            [
                'user_id' => 3,
                'title'   => '第一次嘗試 Laravel + Vue',
                'content' => "之前都只寫純 Laravel + Blade，最近想試試前後端分離。\n\nVue 3 + Pinia + Vue Router 配 Laravel API 真的好寫，前後端各自獨立 deploy 也方便很多。學習曲線比想像中平緩！",
            ],
            [
                'user_id' => 2,
                'title'   => '咖啡因戒斷的痛苦',
                'content' => "決定戒咖啡一個禮拜，結果頭痛了整整 3 天，超級無法工作。\n\n看來真的對咖啡因有依賴...有人有成功戒掉的經驗嗎？是直接戒還是慢慢減量？",
            ],
            [
                'user_id' => 1,
                'title'   => '推薦的開發工具清單',
                'content' => "整理一下我目前在用的工具：\n\n- 編輯器：VS Code\n- Terminal：Windows Terminal + PowerShell\n- DB 工具：DBeaver（免費、跨平台）\n- API 測試：Postman / Thunder Client\n- 容器：Docker Desktop\n\n大家用什麼？",
            ],
            [
                'user_id' => 3,
                'title'   => '貓主子又生氣了',
                'content' => "只是稍微離開沙發 5 分鐘去廚房，回來就被她翻肚噴氣 + 甩尾，到底是哪裡得罪她了？\n\n養貓的各位，你們家主子也這麼難伺候嗎？",
            ],
        ];

        // 從 10 天前開始，每篇間隔 1 天，最新一篇是今天
        $daysAgo = count($articles) - 1;
        foreach ($articles as $data) {
            $createdAt = now()->subDays($daysAgo--)->subMinutes(rand(0, 1440));
            Article::create(array_merge($data, [
                'state'      => 'published',
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]));
        }
    }
}
